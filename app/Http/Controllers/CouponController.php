<?php
namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    // التحقق من الكوبون عبر AJAX
    public function apply(Request $request)
    {
        $request->validate(['code' => 'required|string', 'total' => 'required|numeric']);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'الكوبون غير موجود']);
        }

        $check = $coupon->isValid((float) $request->total);

        if (!$check['valid']) {
            return response()->json($check);
        }

        $discount = $coupon->calcDiscount((float) $request->total);
        $newTotal = round((float) $request->total - $discount, 2);

        return response()->json([
            'valid'       => true,
            'message'     => $check['message'],
            'discount'    => $discount,
            'new_total'   => $newTotal,
            'description' => $coupon->description,
        ]);
    }
}
