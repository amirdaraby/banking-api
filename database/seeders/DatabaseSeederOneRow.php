<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\TransactionCost;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeederOneRow extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1)->create()
            ->each(function ($user) {
                Account::factory(1)
                    ->create(['user_id' => $user->id])->each(function ($account) {
                        Card::factory(1)->create(['account_id' => $account->id])->each(function ($card) {
                            Transaction::factory(1)->create(['card_id' => $card->id, 'updated_at' => now()->subMinutes(random_int(0, 10))])->each(function ($transaction) {
                                TransactionCost::factory()->create(['transaction_id' => $transaction->id]);
                            });
                        });
                    });
            });
    }
}
