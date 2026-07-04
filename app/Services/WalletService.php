<?php

namespace App\Services;

use App\Exceptions\Wallet\InsufficientBalanceException;
use App\Models\{User, Wallet, WalletTransaction};
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * أرجع محفظة المستخدم، أو أنشئها إن لم تكن موجودة.
     */
    public function getOrCreateWallet(User $user): Wallet
    {
        return Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance'  => 0]
        );
    }

    /**
     * أضف رصيداً للمحفظة وسجّل العملية.
     *
     * @throws \Throwable
     */
    public function credit(
        Wallet  $wallet,
        float   $amount,
        string  $sourceType,
        mixed   $sourceId    = null,
        ?string $description = null
    ): WalletTransaction {
        return DB::transaction(function () use ($wallet, $amount, $sourceType, $sourceId, $description) {
            // قفل الصف لمنع race conditions
            $wallet = Wallet::lockForUpdate()->findOrFail($wallet->id);

            $wallet->balance = round($wallet->balance + $amount, 2);
            $wallet->save();

            return WalletTransaction::create([
                'wallet_id'    => $wallet->id,
                'type'         => 'credit',
                'amount'       => $amount,
                'balance_after' => $wallet->balance,
                'source_type'  => $sourceType,
                'source_id'    => $sourceId,
                'description'  => $description,
            ]);
        });
    }

    /**
     * اخصم رصيداً من المحفظة وسجّل العملية.
     * يرمي InsufficientBalanceException إذا كان الرصيد غير كافٍ.
     *
     * @throws InsufficientBalanceException
     * @throws \Throwable
     */
    public function debit(
        Wallet  $wallet,
        float   $amount,
        string  $sourceType,
        mixed   $sourceId    = null,
        ?string $description = null
    ): WalletTransaction {
        return DB::transaction(function () use ($wallet, $amount, $sourceType, $sourceId, $description) {
            // قفل الصف لمنع race conditions
            $wallet = Wallet::lockForUpdate()->findOrFail($wallet->id);

            if ($wallet->balance < $amount) {
                throw new InsufficientBalanceException($amount, $wallet->balance);
            }

            $wallet->balance = round($wallet->balance - $amount, 2);
            $wallet->save();

            return WalletTransaction::create([
                'wallet_id'    => $wallet->id,
                'type'         => 'debit',
                'amount'       => $amount,
                'balance_after' => $wallet->balance,
                'source_type'  => $sourceType,
                'source_id'    => $sourceId,
                'description'  => $description,
            ]);
        });
    }
}
