<?php
namespace App\Http\Controllers;

use App\Models\{Wishlist, Product};
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index() {
        $items = auth()->user()
                       ->wishlist()
                       ->with('product.category')
                       ->latest()
                       ->get();
        return view('wishlist.index', compact('items'));
    }

    public function toggle(Product $product) {
        $user    = auth()->user();
        $exists  = $user->wishlist()->where('product_id', $product->id)->first();

        if ($exists) {
            $exists->delete();
            $message    = __('shop.wishlist_removed');
            $inWishlist = false;
        } else {
            Wishlist::create([
                'user_id'    => $user->id,
                'product_id' => $product->id,
            ]);
            $message    = __('shop.wishlist_added');
            $inWishlist = true;
        }

        if (request()->ajax()) {
            return response()->json([
                'in_wishlist' => $inWishlist,
                'message'     => $message,
                'count'       => $user->wishlistCount(),
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroy(Wishlist $wishlist) {
        if ($wishlist->user_id !== auth()->id()) abort(403);
        $wishlist->delete();
        return redirect()->back()->with('success', __('shop.wishlist_removed'));
    }
}
