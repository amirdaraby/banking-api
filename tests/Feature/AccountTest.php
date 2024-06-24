<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function testAccountIndexResponsesHttpNotFoundWhenThereIsNoAccountInDatabase(): void
    {
        $response = $this->getJson(route('v1.accounts.index'));
        $response->assertNotFound();
    }

    public function testAccountIndexResponsesHttpOk(): void
    {
        Account::factory()->createMany(10);

        $response = $this->getJson(route('v1.accounts.index'));
        $response->assertOk();
    }

    public function testAccountStoreResponsesValidationErrors(): void
    {
        $account = Account::factory()->create();
        $response = $this->postJson(route('v1.accounts.store'), [
            'number' => $account->number,
            'user_id' => $account->user_id,
        ]);

        $response->assertUnprocessable();
    }

    public function testAccountStoreResponsesHttpCreated(): void
    {
        $response = $this->postJson(route('v1.accounts.store'), [
            'number' => '1234567891234567',
            'user_id' => User::factory()->create()->id,
        ]);

        $this->assertDatabaseCount('accounts', 1);
        $response->assertCreated();
    }

    public function testAccountShowResponsesHttpNotFoundWhenThereIsNoAccountInDatabase(): void
    {
        $response = $this->getJson(route('v1.accounts.show', ['account_id' => 1]));
        $response->assertNotFound();
    }

    public function testAccountShowResponsesHttpOk(): void
    {
        $account = Account::factory()->create();
        $response = $this->getJson(route('v1.accounts.show', ['account_id' => $account->id]));
        $response->assertOk();
    }

    public function testAccountUpdateResponsesValidationErrors(): void
    {
        $account = Account::factory()->create(['number' => '09303557608']);
        $account2 = Account::factory()->create(['number' => '09303557601']);

        $response = $this->putJson(route('v1.accounts.update', ['account_id' => $account2->id]), ['number' => $account->number]);

        $response->assertUnprocessable();
    }

    public function testAccountUpdateResponsesHttpNotFoundWhenThereIsNoAccountInDatabase(): void
    {
        $user = User::factory()->create();
        $response = $this->putJson(route('v1.accounts.update', ['account_id' => 1]), ['number' => '89898989', 'user_id' => $user->id]);
        $response->assertNotFound();
    }

    public function testAccountUpdateResponsesHttpAccepted(): void
    {
        $account = Account::factory()->create();
        $response = $this->putJson(route('v1.accounts.update', ['account_id' => $account->id]), [
            'number' => $account->number,
        ]);
        $response->assertAccepted();
    }

    public function testAccountDeleteResponsesHttpNotFoundWhenThereIsNoAccountInDatabase(): void
    {
        $response = $this->deleteJson(route('v1.accounts.destroy', ['account_id' => 1]));
        $response->assertNotFound();
    }

    public function testAccountDeleteResponsesHttpAcceptedAndDeletesUser(): void
    {
        $account = Account::factory()->create();
        $response = $this->deleteJson(route('v1.accounts.destroy', ['account_id' => $account->id]));
        $response->assertAccepted();
        $this->assertSoftDeleted('accounts', ['id' => $account->id]);
    }
}
