<?php

namespace Database\Seeders;

use App\Models\{User, Wallet, RedeemCode};
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        // أنشئ محفظة لكل مستخدم موجود ليس عنده محفظة
        User::whereDoesntHave('wallet')->each(function (User $user) {
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
            ]);
        });

        // أكواد شحن تجريبية
        $admin = User::where('is_admin', true)->first();

        $codes = [
            ['amount' => 10,  'batch_note' => 'دفعة تجريبية'],
            ['amount' => 25,  'batch_note' => 'دفعة تجريبية'],
            ['amount' => 50,  'batch_note' => 'دفعة تجريبية'],
            ['amount' => 100, 'batch_note' => 'دفعة تجريبية'],
        ];

        foreach ($codes as $c) {
            RedeemCode::create([
                'code'       => strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)),
                'amount'     => $c['amount'],
                'batch_note' => $c['batch_note'],
                'created_by' => $admin?->id,
            ]);
        }
    }
}
