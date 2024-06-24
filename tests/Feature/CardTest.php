<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Card;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CardTest extends TestCase
{
    use RefreshDatabase;

    public function testCardIndexResponsesHttpNotFoundWhenThereIsNoCard(): void
    {
        $response = $this->getJson(route('v1.cards.index'));
        $response->assertNotFound();
    }

    public function testCardIndexResponsesHttpOk(): void
    {
        Card::factory(10)->create();

        $response = $this->getJson(route('v1.cards.index'));
        $response->assertOk();
    }

    public function testCardStoreResponsesValidationErrors(): void
    {
        $response = $this->postJson(route('v1.cards.store'), []);
        $response->assertUnprocessable();
    }

    public function testCardStoreResponsesHttpCreated(): void
    {
        $account = Account::factory()->create();
        $response = $this->postJson(route('v1.cards.store'), [
            'account_id' => $account->id,
            'number' => '5022291321937425',
            'expiration_year' => '2025',
            'expiration_month' => '12',
            'cvv2' => '123',
            'password' => '1234',
        ]);

        $response->assertCreated();
        $this->assertDatabaseCount('cards', 1);
    }

    public function testCardShowResponsesHttpNotFound(): void
    {
        $response = $this->getJson(route('v1.cards.show', ['id' => 999]));
        $response->assertNotFound();
    }

    public function testCardShowResponsesHttpOk(): void
    {
        $card = Card::factory()->create();
        $response = $this->getJson(route('v1.cards.show', ['id' => $card->id]));
        $response->assertOk();
    }

    public function testCardUpdateResponsesHttpNotFound(): void
    {
        $account = Account::factory()->create();
        $response = $this->putJson(route('v1.cards.update', ['id' => 1]), [
            'account_id' => $account->id,
            'number' => '5022291321937425',
            'expiration_year' => '2025',
            'expiration_month' => '12',
            'cvv2' => '123',
            'password' => '1234',
        ]);
        $response->assertNotFound();
    }

    public function testCardUpdateResponsesValidationError(): void
    {
        $card = Card::factory()->create();
        $response = $this->putJson(route('v1.cards.update', ['id' => $card->id]), [
            'account_id' => $card->account_id,
            'number' => '5022291321937429', // invalid
            'expiration_year' => '2025',
            'expiration_month' => '12',
            'cvv2' => '123',
            'password' => '1234',
        ]);

        $response->assertUnprocessable();
    }

    public function testCardUpdateResponsesHttpAccepted(): void
    {
        $card = Card::factory()->create();
        $response = $this->putJson(route('v1.cards.update', ['id' => $card->id]), [
            'password' => 'someSecurePassword'
        ]);

        $response->assertAccepted();
    }

    public function testCardDeleteResponsesHttpNotFound(): void
    {
        $response = $this->deleteJson(route('v1.cards.destroy', ['id' => 1]));
        $response->assertNotFound();
    }

    public function testCardDeleteResponsesHttpAccepted(): void
    {
        $card = Card::factory()->create();
        $this->assertDatabaseCount('cards', 1);
        $response = $this->deleteJson(route('v1.cards.destroy', ['id' => $card->id]));
        $response->assertAccepted();
        $this->assertSoftDeleted('cards', ['id' => $card->id]);
    }
}
