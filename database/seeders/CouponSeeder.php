<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder {
    public function run(): void {
        $coupons = [
            [
                'code'       => 'WELCOME10',
                'type'       => 'percentage',
                'value'      => 10,
                'min_order'  => 50,
                'max_uses'   => 100,
                'active'     => true,
                'expires_at' => now()->addMonths(3),
            ],
            [
                'code'       => 'SAVE50',
                'type'       => 'fixed',
                'value'      => 50,
                'min_order'  => 200,
                'max_uses'   => 50,
                'active'     => true,
                'expires_at' => now()->addMonths(1),
            ],
            [
                'code'       => 'VIP25',
                'type'       => 'percentage',
                'value'      => 25,
                'min_order'  => 100,
                'max_uses'   => 0,  // بلا حد
                'active'     => true,
                'expires_at' => now()->addYear(),
            ],
        ];

        foreach ($coupons as $c) Coupon::create($c);
    }
}
