<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index() {
        $reviews = Review::with(['product', 'user'])
                         ->latest()
                         ->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function destroy(Review $review) {
        $review->delete();
        return redirect()->back()->with('success', 'تم حذف التقييم');
    }

    public function toggle(Review $review) {
        $review->update(['approved' => !$review->approved]);
        return redirect()->back()->with('success', 'تم تحديث حالة التقييم');
    }
}
