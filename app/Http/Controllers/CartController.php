<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller {
    public function index() {
        $cart  = session('cart', []);
        $total = collect($cart)->sum(fn($i) => $i['price']);
        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, Product $product) {
        $cart = session('cart', []);
        $id   = $product->id;

        if (!isset($cart[$id])) {
            $cart[$id] = [
                'name'  => $product->name,
                'price' => $product->price,
            ];
        }

        session(['cart' => $cart]);
        return redirect()->back()->with('success', __('shop.cart_added'));
    }

    public function remove($id) {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);
        return redirect()->route('cart.index')->with('success', __('shop.cart_removed'));
    }

    public function clear() {
        session()->forget('cart');
        return redirect()->route('cart.index');
    }
}
