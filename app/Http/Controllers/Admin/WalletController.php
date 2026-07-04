<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{RedeemCode, User, Wallet};
use App\Services\{RedeemCodeService, WalletService};
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(
        private readonly WalletService     $walletService,
        private readonly RedeemCodeService $redeemCodeService
    ) {}

    // ── أكواد الشحن ─────────────────────────────────────────

    /** قائمة الأكواد مع فلاتر */
    public function codes(Request $request)
    {
        $query = RedeemCode::with(['usedByUser', 'createdByUser'])->latest();

        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            match($request->status) {
                'used'     => $query->where('is_used', true),
                'unused'   => $query->where('is_used', false)->where('is_disabled', false)
                                    ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now())),
                'disabled' => $query->where('is_disabled', true),
                'expired'  => $query->where('is_used', false)->where('is_disabled', false)
                                    ->where('expires_at', '<=', now()),
                default    => null,
            };
        }

        $codes = $query->paginate(20)->withQueryString();

        return view('admin.wallet.codes', compact('codes'));
    }

    /** فورم توليد الأكواد */
    public function generateForm()
    {
        return view('admin.wallet.generate');
    }

    /** توليد الأكواد */
    public function generate(Request $request)
    {
        $data = $request->validate([
            'amount'     => ['required', 'numeric', 'min:1', 'max:10000'],
            'count'      => ['required', 'integer', 'min:1', 'max:100'],
            'batch_note' => ['nullable', 'string', 'max:255'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ], [
            'amount.required'   => 'المبلغ مطلوب.',
            'amount.min'        => 'أقل مبلغ مسموح هو $1.',
            'amount.max'        => 'أعلى مبلغ مسموح هو $10,000.',
            'count.required'    => 'عدد الأكواد مطلوب.',
            'count.min'         => 'يجب توليد كود واحد على الأقل.',
            'count.max'         => 'لا يمكن توليد أكثر من 100 كود دفعة واحدة.',
            'expires_at.after'  => 'تاريخ الانتهاء يجب أن يكون في المستقبل.',
        ]);

        $codes = $this->redeemCodeService->generate(
            amount:    (float) $data['amount'],
            note:      $data['batch_note'] ?? null,
            expiresAt: isset($data['expires_at']) ? new \DateTime($data['expires_at']) : null,
            count:     (int) $data['count'],
            createdBy: auth()->id(),
        );

        return view('admin.wallet.generate', compact('codes'))
            ->with('generated', true);
    }

    /** تعطيل كود */
    public function disable(RedeemCode $code)
    {
        if ($code->is_used) {
            return back()->with('error', 'لا يمكن تعطيل كود تم استخدامه مسبقاً.');
        }

        $code->update(['is_disabled' => !$code->is_disabled]);

        $msg = $code->is_disabled ? 'تم تعطيل الكود.' : 'تم تفعيل الكود مجدداً.';
        return back()->with('success', $msg);
    }

    // ── أرصدة المستخدمين ─────────────────────────────────────

    /** قائمة المستخدمين وأرصدتهم */
    public function users(Request $request)
    {
        $query = User::where('is_admin', false)->with('wallet');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->orderByDesc(
            Wallet::select('balance')->whereColumn('user_id', 'users.id')->limit(1)
        )->paginate(20)->withQueryString();

        return view('admin.wallet.users', compact('users'));
    }

    /** سجل عمليات مستخدم معين */
    public function userTransactions(User $user)
    {
        $wallet = $this->walletService->getOrCreateWallet($user);
        $transactions = $wallet->transactions()->paginate(20);
        return view('admin.wallet.transactions', compact('user', 'wallet', 'transactions'));
    }

    /** تعديل رصيد يدوي — فورم */
    public function adjustForm(User $user)
    {
        $wallet = $this->walletService->getOrCreateWallet($user);
        return view('admin.wallet.adjust', compact('user', 'wallet'));
    }

    /** تعديل رصيد يدوي — تنفيذ */
    public function adjust(Request $request, User $user)
    {
        $data = $request->validate([
            'type'        => ['required', 'in:credit,debit'],
            'amount'      => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string', 'max:255'],
        ], [
            'type.required'        => 'نوع العملية مطلوب.',
            'type.in'              => 'نوع العملية غير صحيح.',
            'amount.required'      => 'المبلغ مطلوب.',
            'amount.min'           => 'المبلغ يجب أن يكون أكبر من صفر.',
            'description.required' => 'سبب التعديل مطلوب.',
        ]);

        $wallet = $this->walletService->getOrCreateWallet($user);

        try {
            if ($data['type'] === 'credit') {
                $this->walletService->credit(
                    wallet:      $wallet,
                    amount:      (float) $data['amount'],
                    sourceType:  'admin_adjustment',
                    sourceId:    auth()->id(),
                    description: $data['description'],
                );
            } else {
                $this->walletService->debit(
                    wallet:      $wallet,
                    amount:      (float) $data['amount'],
                    sourceType:  'admin_adjustment',
                    sourceId:    auth()->id(),
                    description: $data['description'],
                );
            }

            $action = $data['type'] === 'credit' ? 'إضافة' : 'خصم';
            return redirect()
                ->route('admin.wallet.user-transactions', $user)
                ->with('success', "تم {$action} \${$data['amount']} من رصيد {$user->name} بنجاح.");

        } catch (\App\Exceptions\Wallet\InsufficientBalanceException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
