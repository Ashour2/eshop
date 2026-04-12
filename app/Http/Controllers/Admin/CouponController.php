<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index() {
        $coupons = Coupon::latest()->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create() {
        return view('admin.coupons.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'code'       => 'required|string|unique:coupons,code|max:50',
            'type'       => 'required|in:percentage,fixed',
            'value'      => 'required|numeric|min:0.01',
            'min_order'  => 'nullable|numeric|min:0',
            'max_uses'   => 'nullable|integer|min:0',
            'expires_at' => 'nullable|date|after:today',
        ], [
            'code.unique'        => 'هذا الكود مستخدم مسبقاً',
            'expires_at.after'   => 'تاريخ الانتهاء يجب أن يكون في المستقبل',
        ]);

        $data['code']      = strtoupper($data['code']);
        $data['active']    = $request->has('active');
        $data['min_order'] = $data['min_order'] ?? 0;
        $data['max_uses']  = $data['max_uses']  ?? 0;

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'تم إضافة الكوبون بنجاح');
    }

    public function edit(Coupon $coupon) {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon) {
        $data = $request->validate([
            'code'       => 'required|string|unique:coupons,code,' . $coupon->id . '|max:50',
            'type'       => 'required|in:percentage,fixed',
            'value'      => 'required|numeric|min:0.01',
            'min_order'  => 'nullable|numeric|min:0',
            'max_uses'   => 'nullable|integer|min:0',
            'expires_at' => 'nullable|date',
        ]);

        $data['code']      = strtoupper($data['code']);
        $data['active']    = $request->has('active');
        $data['min_order'] = $data['min_order'] ?? 0;
        $data['max_uses']  = $data['max_uses']  ?? 0;

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')
                         ->with('success', 'تم تحديث الكوبون');
    }

    public function destroy(Coupon $coupon) {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')
                         ->with('success', 'تم حذف الكوبون');
    }
}
