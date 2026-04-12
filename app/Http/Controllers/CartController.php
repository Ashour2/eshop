<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller {
    public function index() {
        $cart = session('cart', []);
        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, Product $product) {
        $cart = session('cart', []);
        $id = $product->id;

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name'     => $product->name,
                'price'    => $product->price,
                'quantity' => 1,
                'image'    => $product->image_url,
            ];
        }

        session(['cart' => $cart]);
        return redirect()->back()->with('success', 'تمت الإضافة للسلة!');
    }

    public function update(Request $request, $id) {
        $cart = session('cart', []);
        if (isset($cart[$id])) {
            $qty = (int) $request->quantity;
            if ($qty <= 0) unset($cart[$id]);
            else $cart[$id]['quantity'] = $qty;
        }
        session(['cart' => $cart]);
        return redirect()->route('cart.index');
    }

    public function remove($id) {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);
        return redirect()->route('cart.index')->with('success', 'تم حذف المنتج من السلة');
    }

    public function clear() {
        session()->forget('cart');
        return redirect()->route('cart.index');
    }
}
