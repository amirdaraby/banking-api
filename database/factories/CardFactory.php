<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => Account::factory(),
            'number' => $this->faker->unique()->creditCardNumber('Visa'),
            'expiration_year' => $this->faker->numberBetween(2000, 3000),
            'expiration_month' => $this->faker->numberBetween(1,12),
            'cvv2' => $this->faker->numerify(),
            'password' => 'password',
        ];
    }
}
