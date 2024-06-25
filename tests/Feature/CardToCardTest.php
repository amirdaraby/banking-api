<?php

namespace Tests\Feature;


use App\Enums\TransactionDirection;
use App\Enums\TransactionStatus;
use App\Events\CardToCardSuccessEvent;
use App\Models\Account;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\TransactionCost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CardToCardTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCardToCardResponsesErrorWhenCardIsNotFound(): void
    {
        $response = $this->postJson(route('v1.transactions.card-to-card'), [
            "source_card" => $this->faker->unique()->creditCardNumber('Visa'),
            "destination_card" => $this->faker->unique()->creditCardNumber('Visa'),
            "amount" => config('banking.card_to_card.min_amount') + 20000,
        ]);

        $response->assertNotFound();
    }

    public function testCardToCardResponsesErrorWhenCardIsNotValid(): void
    {
        $response = $this->postJson(route('v1.transactions.card-to-card'), [
            'source_card' => '1234123412341234',
            'destination_card' => '123412341234',
            'amount' => config('banking.card_to_card.min_amount') + 20000,
        ]);

        $response->assertUnprocessable();
    }

    public function testCardToCardResponsesErrorWhenBothCardsAreSame(): void
    {
        $cardNumber = $this->faker->creditCardNumber();

        $response = $this->postJson(route('v1.transactions.card-to-card'), [
            'source_card' => $cardNumber,
            'destination_card' => $cardNumber,
            'amount' => config('banking.card_to_card.min_amount') + 20000,
        ]);

        $response->assertBadRequest();
    }

    public function testCardToCardResponsesErrorWhenSourceCardBalanceIsNotEnough(): void
    {
        $account = Account::factory()->create(['balance' => 1000]);
        $sourceCard = Card::factory()->for($account)->create();

        $destinationCard = Card::factory()->create();

        $response = $this->postJson(route('v1.transactions.card-to-card'), [
            'source_card' => $sourceCard->number,
            'destination_card' => $destinationCard->number,
            'amount' => config('banking.card_to_card.min_amount') + 20000,
        ]);

        $response->assertBadRequest();
    }

    public function testCardToCardResponsesSuccessfully(): void
    {
        Event::fake();

        $balance = config('banking.card_to_card.max_amount') + config('banking.card_to_card.transaction_cost');
        $sourceAccount = Account::factory()->create(['balance' => $balance]);
        $destinationAccount = Account::factory()->create();

        $sourceAccountBalanceBeforeTransaction = $sourceAccount->balance;
        $destinationAccountBalanceAfterTransaction = $destinationAccount->balance;

        $transactionCost = config('banking.card_to_card.transaction_cost');
        $transactionAmount = random_int(config('banking.card_to_card.min_amount'), config('banking.card_to_card.max_amount'));

        $sourceCard = Card::factory()->for($sourceAccount)->create();

        $destinationCard = Card::factory()->for($destinationAccount)->create();

        $response = $this->postJson(route('v1.transactions.card-to-card'), [
            'source_card' => $sourceCard->number,
            'destination_card' => $destinationCard->number,
            'amount' => $transactionAmount,
        ]);

        $response->assertOk();

        $sourceAccount->refresh();
        $destinationAccount->refresh();

        $this->assertEquals($sourceAccountBalanceBeforeTransaction - ($transactionAmount + $transactionCost), $sourceAccount->balance);
        $this->assertEquals($destinationAccountBalanceAfterTransaction + $transactionAmount, $destinationAccount->balance);

        $this->assertDatabaseHas(Transaction::class, [
            'card_id' => $sourceCard->id,
            'amount' => $transactionAmount,
            'type' => TransactionDirection::SEND->value,
            'status' => TransactionStatus::SUCCESS->value,
        ]);

        $this->assertDatabaseHas(Transaction::class, [
            'card_id' => $destinationCard->id,
            'amount' => $transactionAmount,
            'type' => TransactionDirection::RECEIVE->value,
            'status' => TransactionStatus::SUCCESS->value,
        ]);

        $this->assertDatabaseHas(TransactionCost::class, [
            'transaction_id' => $response->json('data.send_transaction.id'),
            'amount' => $transactionCost,
        ]);


        Event::assertDispatched(CardToCardSuccessEvent::class);
    }
}
