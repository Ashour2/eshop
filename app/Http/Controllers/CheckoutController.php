<?php
namespace App\Http\Controllers;

use App\Models\{Order, OrderItem, Product};
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    // public function __construct() {
    //     $this->middleware('auth')->only(['index', 'store']);
    // }

    public function index() {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        }
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);

        // تعبئة البيانات من حساب المستخدم تلقائياً
        $user = auth()->user();

        return view('checkout.index', compact('cart', 'total', 'user'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'address' => 'required|string',
        ], [
            'name.required'    => 'الاسم مطلوب',
            'email.required'   => 'البريد الإلكتروني مطلوب',
            'email.email'      => 'صيغة البريد الإلكتروني غير صحيحة',
            'address.required' => 'العنوان مطلوب',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('shop.index');

        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);

        $order = Order::create([
            'user_id'          => auth()->id(),   // ← ربط بالمستخدم
            'customer_name'    => $request->name,
            'customer_email'   => $request->email,
            'customer_address' => $request->address,
            'total'            => $total,
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
