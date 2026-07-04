<?php
namespace App\Http\Controllers;

use App\Exceptions\Wallet\InsufficientBalanceException;
use App\Mail\{OrderConfirmed, NewOrderAlert};
use App\Models\{Order, OrderItem, Product, Coupon, User, Setting};
use App\Notifications\{NewOrderNotification, LowStockNotification};
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Mail};

class CheckoutController extends Controller
{
    public function __construct(private readonly WalletService $walletService) {}

    public function index() {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('shop.cart_empty_error'));
        }
        $total  = collect($cart)->sum(fn($i) => $i['price']);
        $user   = auth()->user();
        $wallet = $user ? $this->walletService->getOrCreateWallet($user) : null;

        return view('checkout.index', compact('cart', 'total', 'user', 'wallet'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'address' => 'required|string',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('shop.index');

        $subtotal   = collect($cart)->sum(fn($i) => $i['price']);
        $discount   = 0;
        $couponCode = null;

        // ── تطبيق الكوبون ────────────────────────────────
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();
            if ($coupon && $coupon->isValid($subtotal)['valid']) {
                $discount   = $coupon->calcDiscount($subtotal);
                $couponCode = $coupon->code;
                $coupon->increment('used_count');
            }
        }

        $total = max(0, $subtotal - $discount);

        // ── الدفع من الرصيد ──────────────────────────────
        $payWithWallet = $request->boolean('pay_with_wallet');

        if ($payWithWallet) {
            $wallet = $this->walletService->getOrCreateWallet(auth()->user());

            if ($wallet->balance < $total) {
                return back()
                    ->withInput()
                    ->with('error', "رصيدك غير كافٍ. رصيدك الحالي: \${$wallet->balance}، المطلوب: \${$total}")
                    ->with('open_wallet_modal', true);
            }
        }

        // ── إنشاء الطلب + خصم الرصيد في transaction واحدة ──
        try {
            $order = DB::transaction(function () use (
                $request, $cart, $total, $discount, $couponCode, $payWithWallet
            ) {
                $order = Order::create([
                    'user_id'          => auth()->id(),
                    'customer_name'    => $request->name,
                    'customer_email'   => $request->email,
                    'customer_address' => $request->address,
                    'total'            => $total,
                    'discount'         => $discount,
                    'coupon_code'      => $couponCode,
                    'status'           => 'pending',
                ]);

                foreach ($cart as $productId => $item) {
                    OrderItem::create([
                        'order_id'     => $order->id,
                        'product_id'   => $productId,
                        'product_name' => $item['name'],
                        'price'        => $item['price'],
                        'quantity'     => 1,
                    ]);
                }

                // خصم الرصيد داخل نفس الـ transaction
                if ($payWithWallet && $total > 0) {
                    $wallet = $this->walletService->getOrCreateWallet(auth()->user());
                    $this->walletService->debit(
                        wallet:      $wallet,
                        amount:      $total,
                        sourceType:  'service_purchase',
                        sourceId:    $order->id,
                        description: "شراء الخدمات — طلب #{$order->id}",
                    );
                }

                return $order;
            });

        } catch (InsufficientBalanceException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage())
                ->with('open_wallet_modal', true);
        }

        session()->forget('cart');
        $order->load('items');

        // ── إشعار الأدمن ─────────────────────────────────
        $admins = User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewOrderNotification($order));
        }

        // ── رسالة واتساب ─────────────────────────────────
        $waNumber  = Setting::get('whatsapp_number', '970594048945');
        $waMessage = $this->buildWhatsAppMessage($order, $payWithWallet);

        return redirect()->route('checkout.success', [
            'order'  => $order->id,
            'wa_url' => 'https://wa.me/' . $waNumber . '?text=' . urlencode($waMessage),
        ]);
    }

    public function success(Order $order) {
        $waUrl = request('wa_url');
        return view('checkout.success', compact('order', 'waUrl'));
    }

    private function buildWhatsAppMessage(Order $order, bool $paidWithWallet = false): string
    {
        $lines   = [];
        $lines[] = '🌟 طلب جديد من موقع RoyaTech';
        $lines[] = '━━━━━━━━━━━━━━━━━━━━━━━━';
        $lines[] = '📋 رقم الطلب: #' . $order->id;
        $lines[] = '👤 الاسم: ' . $order->customer_name;
        $lines[] = '📧 البريد: ' . $order->customer_email;
        $lines[] = '📍 العنوان: ' . $order->customer_address;
        $lines[] = '━━━━━━━━━━━━━━━━━━━━━━━━';
        $lines[] = '🛒 الخدمات المطلوبة:';

        foreach ($order->items as $item) {
            $lines[] = "  • {$item->product_name} — $" . number_format($item->price, 2);
        }

        $lines[] = '━━━━━━━━━━━━━━━━━━━━━━━━';

        if ($order->coupon_code) {
            $lines[] = '🎟️ كود الخصم: ' . $order->coupon_code;
            $lines[] = '💸 الخصم: -$' . number_format($order->discount, 2);
        }

        $lines[] = '💰 الإجمالي: $' . number_format($order->total, 2);

        if ($paidWithWallet) {
            $lines[] = '💳 طريقة الدفع: رصيد المحفظة ✓';
        }

        $lines[] = '━━━━━━━━━━━━━━━━━━━━━━━━';
        $lines[] = '⏰ ' . now()->format('Y-m-d H:i');

        return implode("\n", $lines);
    }
}
