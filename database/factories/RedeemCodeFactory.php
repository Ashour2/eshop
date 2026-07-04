<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RedeemCodeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code'        => strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)),
            'amount'      => $this->faker->randomElement([10, 25, 50, 100, 200]),
            'is_used'     => false,
            'is_disabled' => false,
            'used_by'     => null,
            'used_at'     => null,
            'created_by'  => null,
            'batch_note'  => null,
            'expires_at'  => null,
        ];
    }

    public function used(User $user): static
    {
        return $this->state([
            'is_used' => true,
            'used_by' => $user->id,
            'used_at' => now(),
        ]);
    }

    public function expired(): static
    {
        return $this->state([
            'expires_at' => now()->subDay(),
        ]);
    }
}
