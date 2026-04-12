<?php
namespace App\Http\Controllers;

use App\Models\{Order, OrderItem, Product, Coupon};
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index() {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        }
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        $user  = auth()->user();
        return view('checkout.index', compact('cart', 'total', 'user'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'address' => 'required|string',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('shop.index');

        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        $discount = 0;
        $couponCode = null;

        // ── تطبيق الكوبون إذا موجود ──────────────────────
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();
            if ($coupon && $coupon->isValid($subtotal)['valid']) {
                $discount   = $coupon->calcDiscount($subtotal);
                $couponCode = $coupon->code;
                // زيادة عداد الاستخدام
                $coupon->increment('used_count');
            }
        }

        $total = max(0, $subtotal - $discount);

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
                'quantity'     => $item['quantity'],
            ]);
            Product::where('id', $productId)->decrement('stock', $item['quantity']);
        }

        session()->forget('cart');

        return redirect()->route('checkout.success', $order->id);
    }

    public function success(Order $order) {
        return view('checkout.success', compact('order'));
    }
}
