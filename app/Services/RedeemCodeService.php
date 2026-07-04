<?php

namespace App\Services;

use App\Exceptions\Wallet\{
    CodeAlreadyUsedException,
    CodeDisabledException,
    CodeExpiredException,
    CodeNotFoundException
};
use App\Models\{RedeemCode, User};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RedeemCodeService
{
    public function __construct(private readonly WalletService $walletService) {}

    /**
     * ولّد كوداً واحداً أو دفعة أكواد شحن.
     *
     * @return RedeemCode[]
     */
    public function generate(
        float    $amount,
        ?string  $note      = null,
        ?\DateTime $expiresAt = null,
        int      $count     = 1,
        ?int     $createdBy = null
    ): array {
        $codes = [];

        for ($i = 0; $i < $count; $i++) {
            $codes[] = RedeemCode::create([
                'code'       => $this->generateUniqueCode(),
                'amount'     => $amount,
                'batch_note' => $note,
                'expires_at' => $expiresAt,
                'created_by' => $createdBy,
            ]);
        }

        return $codes;
    }

    /**
     * تفعيل كود شحن لمستخدم.
     * — يفشل مع استثناء مخصص إذا كان الكود غير موجود / مستخدم / منتهي / معطّل.
     * — يستخدم lockForUpdate داخل transaction لمنع race conditions.
     *
     * @throws CodeNotFoundException
     * @throws CodeAlreadyUsedException
     * @throws CodeExpiredException
     * @throws CodeDisabledException
     * @throws \Throwable
     */
    public function redeem(string $code, User $user): array
    {
        return DB::transaction(function () use ($code, $user) {
            // lockForUpdate يمنع مستخدمَين من تفعيل نفس الكود بنفس اللحظة
            $redeemCode = RedeemCode::where('code', strtoupper(trim($code)))
                ->lockForUpdate()
                ->first();

            if (!$redeemCode) {
                throw new CodeNotFoundException();
            }

            if ($redeemCode->is_used) {
                throw new CodeAlreadyUsedException();
            }

            if ($redeemCode->is_disabled) {
                throw new CodeDisabledException();
            }

            if ($redeemCode->isExpired()) {
                throw new CodeExpiredException();
            }

            // علّم الكود كمستخدم
            $redeemCode->update([
                'is_used' => true,
                'used_by' => $user->id,
                'used_at' => now(),
            ]);

            // اشحن رصيد المستخدم
            $wallet = $this->walletService->getOrCreateWallet($user);
            $this->walletService->credit(
                wallet:      $wallet,
                amount:      (float) $redeemCode->amount,
                sourceType:  'redeem_code',
                sourceId:    $redeemCode->id,
                description: "شحن رصيد بكود: {$redeemCode->code}"
            );

            // أعد تحميل المحفظة لقراءة الرصيد المحدّث
            $wallet->refresh();

            return [
                'amount'      => $redeemCode->amount,
                'new_balance' => $wallet->balance,
                'message'     => "تم شحن رصيدك بمبلغ \${$redeemCode->amount} بنجاح! رصيدك الحالي: \${$wallet->balance}",
            ];
        });
    }

    /**
     * ولّد كود فريد بصيغة XXXX-XXXX-XXXX.
     */
    private function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(4)) . '-'
                  . strtoupper(Str::random(4)) . '-'
                  . strtoupper(Str::random(4));
        } while (RedeemCode::where('code', $code)->exists());

        return $code;
    }
}
