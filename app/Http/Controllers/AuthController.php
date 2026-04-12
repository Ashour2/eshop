<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ── تسجيل الدخول ─────────────────────────────────────
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            if (Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->intended(route('shop.index'))
                             ->with('success', 'مرحباً بك ' . Auth::user()->name . '!');
        }

        return back()->withErrors(['email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة']);
    }

    // ── إنشاء حساب ───────────────────────────────────────
    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'name.required'      => 'الاسم مطلوب',
            'email.required'     => 'البريد الإلكتروني مطلوب',
            'email.unique'       => 'البريد الإلكتروني مسجّل مسبقاً',
            'password.required'  => 'كلمة المرور مطلوبة',
            'password.min'       => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'كلمتا المرور غير متطابقتين',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => false,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('shop.index')
                         ->with('success', 'تم إنشاء حسابك بنجاح! مرحباً ' . $user->name);
    }

    // ── تسجيل الخروج ─────────────────────────────────────
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('shop.index')
                         ->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
