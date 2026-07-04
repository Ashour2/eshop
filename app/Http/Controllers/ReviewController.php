<?php
namespace App\Http\Controllers;

use App\Models\{Product, Review};
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                             ->with('error', __('shop.review_login_required'));
        }

        $purchased = auth()->user()
            ->orders()
            ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
            ->exists();

        if (!$purchased) {
            return redirect()->back()
                             ->with('error', __('shop.review_must_purchase_first'));
        }

        $exists = Review::where('product_id', $product->id)
                        ->where('user_id', auth()->id())
                        ->exists();

        if ($exists) {
            return redirect()->back()
                             ->with('error', __('shop.review_already_submitted'));
        }

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'product_id' => $product->id,
            'user_id'    => auth()->id(),
            'rating'     => $request->rating,
            'comment'    => $request->comment,
            'approved'   => true,
        ]);

        return redirect()->back()->with('success', __('shop.review_added_success'));
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) abort(403);
        $review->delete();
        return redirect()->back()->with('success', __('shop.review_deleted'));
    }
}
