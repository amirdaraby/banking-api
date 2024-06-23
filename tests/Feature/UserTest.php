<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testUserIndexResponsesHttpNotFoundWhenThereIsNoUserInDatabase(): void
    {
        $response = $this->getJson(route('v1.users.index'));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testUserIndexResponsesHttpOk(): void
    {
        User::factory()->createMany(10);

        $response = $this->getJson(route('v1.users.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testUserStoreResponsesValidationErrors(): void
    {
        $phone = "09303557608";

        User::factory()->create(['phone_number' => $phone, 'name' => 'amir']);

        $response = $this->postJson(route('v1.users.store'), [
            'name' => 'amir',
            'phone_number' => $phone,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUserStoreResponsesHttpCreatedAndCreatesUser(): void
    {
        $response = $this->postJson(route('v1.users.store'), [
            'name' => 'amir',
            'phone_number' => '09303557608',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseCount('users', 1);
    }

    public function testUserUpdateResponsesNotFound(): void
    {
        $response = $this->putJson(route('v1.users.update', ['user_id' => 1]), [
            'name' => 'amir',
            'phone_number' => '09303557608',
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testUserUpdateResponsesValidationErrors(): void
    {
        $user = User::factory()->create(['phone_number' => '09303557608']);
        $user2 = User::factory()->create(['phone_number' => '09303557601']);

        $response = $this->putJson(route('v1.users.update', ['user_id' => $user2->id]), ['phone_number' => $user->phone_number]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUserUpdateResponsesHttpAccepted(): void
    {
        $user = User::factory()->create();

        $response = $this->putJson(route('v1.users.update', ['user_id' => $user->id]), ['name' => 'amir', 'phone_number' => $user->phone_number]);
        $response->assertStatus(Response::HTTP_ACCEPTED);


        $response = $this->putJson(route('v1.users.update', ['user_id' => $user->id]), ['name' => 'amir', 'phone_number' => '09303557601']);
        $response->assertStatus(Response::HTTP_ACCEPTED);
    }

    public function testUserDeleteResponsesHttpNotFound(): void
    {
        $response = $this->deleteJson(route('v1.users.destroy', ['user_id' => 1]));
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testUserDeleteResponsesHttpAcceptedAndDeletesUser(): void
    {
        $user = User::factory()->create();
        $response = $this->deleteJson(route('v1.users.destroy', ['user_id' => $user->id]));
        $response->assertStatus(Response::HTTP_ACCEPTED);

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

}
