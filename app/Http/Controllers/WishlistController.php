<?php
namespace App\Http\Controllers;

use App\Models\{Wishlist, Product};
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // صفحة المفضلة
    public function index() {
        $items = auth()->user()
                       ->wishlist()
                       ->with('product.category')
                       ->latest()
                       ->get();
        return view('wishlist.index', compact('items'));
    }

    // إضافة أو إزالة من المفضلة (Toggle)
    public function toggle(Product $product) {
        $user    = auth()->user();
        $exists  = $user->wishlist()->where('product_id', $product->id)->first();

        if ($exists) {
            $exists->delete();
            $message = 'تم إزالة المنتج من المفضلة';
            $inWishlist = false;
        } else {
            Wishlist::create([
                'user_id'    => $user->id,
                'product_id' => $product->id,
            ]);
            $message = 'تم إضافة المنتج للمفضلة ❤️';
            $inWishlist = true;
        }

        // إذا كان الطلب AJAX
        if (request()->ajax()) {
            return response()->json([
                'in_wishlist' => $inWishlist,
                'message'     => $message,
                'count'       => $user->wishlistCount(),
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    // حذف من المفضلة
    public function destroy(Wishlist $wishlist) {
        if ($wishlist->user_id !== auth()->id()) abort(403);
        $wishlist->delete();
        return redirect()->back()->with('success', 'تم إزالة المنتج من المفضلة');
    }
}
