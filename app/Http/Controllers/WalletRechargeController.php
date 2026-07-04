<?php

namespace App\Http\Controllers;

use App\Models\{Setting, User, WalletRechargeRequest};
use App\Notifications\WalletRechargeRequestNotification;
use Illuminate\Http\Request;

class WalletRechargeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:10000'],
        ], [
            'amount.required' => 'يرجى إدخال المبلغ المطلوب.',
            'amount.min'      => 'أقل مبلغ يمكن طلبه هو $1.',
            'amount.max'      => 'أعلى مبلغ يمكن طلبه هو $10,000.',
            'amount.numeric'  => 'المبلغ يجب أن يكون رقماً.',
        ]);

        $user   = auth()->user();
        $amount = (float) $request->amount;

        // سجّل الطلب في قاعدة البيانات
        $rechargeRequest = WalletRechargeRequest::create([
            'user_id' => $user->id,
            'amount'  => $amount,
            'status'  => 'pending',
        ]);

        // أرسل إشعاراً لكل الأدمنز
        User::where('is_admin', true)->each(
            fn(User $admin) => $admin->notify(new WalletRechargeRequestNotification($rechargeRequest))
        );

        // ابنِ رسالة واتساب جاهزة
        $waNumber = Setting::get('whatsapp_number', '970594048945');
        $waMessage = implode("\n", [
            '💳 طلب شحن رصيد — RoyaTech',
            '━━━━━━━━━━━━━━━━━━━━━━━━',
            '👤 الاسم: ' . $user->name,
            '📧 البريد: ' . $user->email,
            '💰 المبلغ المطلوب: $' . number_format($amount, 2),
            '🔢 رقم الطلب: #' . $rechargeRequest->id,
            '━━━━━━━━━━━━━━━━━━━━━━━━',
            'أرجو إرسال كود الشحن لإتمام العملية.',
        ]);

        $waUrl = 'https://wa.me/' . $waNumber . '?text=' . urlencode($waMessage);

        return redirect($waUrl);
    }
}
