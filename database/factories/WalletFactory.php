<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'balance' => $this->faker->randomFloat(2, 0, 500),
        ];
    }

    public function empty(): static
    {
        return $this->state(['balance' => 0]);
    }
}
