<?php

namespace Database\Factories;

use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'card_id' => Card::factory(),
            'amount' => $this->faker->numberBetween(10_000, 500_000_000),
            'type' => $this->faker->randomElement(TransactionDirection::values()),
            'status' => TransactionStatus::SUCCESS->value,
        ];
    }
}
