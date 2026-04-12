<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CheckoutController extends Controller {
    public function index() {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('cart.index')->with('error', 'السلة فارغة');
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        return view('checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'address' => 'required|string',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('shop.index');

        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);

        $order = Order::create([
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
            // تخفيض المخزون
            Product::where('id', $productId)->decrement('stock', $item['quantity']);
        }

        session()->forget('cart');
        return redirect()->route('checkout.success', $order->id);
    }

    public function success(Order $order) {
        return view('checkout.success', compact('order'));
    }
}
