<?php
namespace App\Http\Controllers;

use App\Models\{Product, Review};
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        // لازم يكون مسجّل
        if (!auth()->check()) {
            return redirect()->route('login')
                             ->with('error', 'سجّل دخول أولاً لتتمكن من التقييم');
        }

        // تحقق إذا اشترى المنتج
        $purchased = auth()->user()
            ->orders()
            ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
            ->exists();

        if (!$purchased) {
            return redirect()->back()
                             ->with('error', 'يمكنك التقييم فقط بعد شراء هذا المنتج');
        }

        // تحقق إذا قيّم مسبقاً
        $exists = Review::where('product_id', $product->id)
                        ->where('user_id', auth()->id())
                        ->exists();

        if ($exists) {
            return redirect()->back()
                             ->with('error', 'لقد قيّمت هذا المنتج مسبقاً');
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

        return redirect()->back()->with('success', 'شكراً! تم إضافة تقييمك بنجاح ⭐');
    }

    public function destroy(Review $review)
    {
        // العميل يحذف تقييمه فقط
        if ($review->user_id !== auth()->id()) abort(403);
        $review->delete();
        return redirect()->back()->with('success', 'تم حذف تقييمك');
    }
}
