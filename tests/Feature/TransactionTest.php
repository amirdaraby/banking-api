<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeederOneRow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{

    use RefreshDatabase;

    public function testTopUsersResponseHttpNotFoundWhenDatabaseIsEmpty(): void
    {
        $response = $this->getJson(route('v1.transactions.top-users'));

        $response->assertNotFound();
    }

    public function testTopUsersResponsesHttpOk(): void
    {
        $this->seed([DatabaseSeederOneRow::class]);

        $response = $this->getJson(route('v1.transactions.top-users'));

        $response->assertOk();
    }
}
