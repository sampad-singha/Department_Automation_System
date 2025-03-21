<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\postJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can logout successfully', function () {
    // Create and authenticate a user
    $user = User::factory()->create();
    $token = $user->createToken('test-token')->plainTextToken;

    // Make logout request with valid token
    $response = postJson('/api/auth/logout', [], [
        'Authorization' => 'Bearer ' . $token
    ]);

    // Assert successful response
    $response->assertStatus(200)
        ->assertJson(['message' => 'User logged out successfully']);

    // Verify token was deleted
    $this->assertCount(0, $user->tokens);
});

test('unauthenticated user cannot logout', function () {
    // Make logout request without authentication
    $response = postJson('/api/auth/logout');

    // Assert unauthorized response
    $response->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

test('logging out deletes only current token', function () {
    // Create user with multiple tokens
    $user = User::factory()->create();

    // Create tokens and extract plain text parts
    $token1 = $user->createToken('token1')->plainTextToken;
    $plainToken1 = explode('|', $token1)[1];

    $token2 = $user->createToken('token2')->plainTextToken;
    $plainToken2 = explode('|', $token2)[1];

    // Logout with first token
    postJson('/api/auth/logout', [], [
        'Authorization' => 'Bearer ' . $token1
    ])->assertStatus(200);

    // Verify first token was deleted but second remains
    $this->assertDatabaseMissing('personal_access_tokens', [
        'token' => hash('sha256', $plainToken1)
    ]);

    $this->assertDatabaseHas('personal_access_tokens', [
        'token' => hash('sha256', $plainToken2)
    ]);
});
