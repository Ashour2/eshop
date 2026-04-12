<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    // ── صفحة حسابي ───────────────────────────────────────
    public function index() {
        $user          = Auth::user();
        $orders_count  = $user->orders()->count();
        $total_spent   = $user->orders()
                              ->where('status', '!=', 'cancelled')
                              ->sum('total');
        $recent_orders = $user->orders()->latest()->take(3)->get();

        return view('account.index', compact('user', 'orders_count', 'total_spent', 'recent_orders'));
    }

    // ── تعديل البيانات الشخصية ────────────────────────────
    public function update(Request $request) {
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ], [
            'name.required'  => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.unique'   => 'البريد الإلكتروني مستخدم من حساب آخر',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'تم تحديث بياناتك بنجاح');
    }

    // ── تغيير كلمة المرور ────────────────────────────────
    public function changePassword(Request $request) {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة',
            'password.min'              => 'كلمة المرور الجديدة 8 أحرف على الأقل',
            'password.confirmed'        => 'كلمتا المرور غير متطابقتين',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'تم تغيير كلمة المرور بنجاح');
    }

    // ── طلباتي ───────────────────────────────────────────
    public function orders() {
        $orders = Auth::user()
                      ->orders()
                      ->with('items')
                      ->latest()
                      ->paginate(10);

        return view('account.orders', compact('orders'));
    }

    // ── تفاصيل طلب ───────────────────────────────────────
    public function orderShow($id) {
        $order = Auth::user()
                     ->orders()
                     ->with('items')
                     ->findOrFail($id);

        return view('account.order-show', compact('order'));
    }
}
