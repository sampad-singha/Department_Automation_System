<?php

use App\Models\User;
use Illuminate\Support\Facades\Password;
use function Pest\Laravel\postJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Successful password reset
test('resets password with valid token', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $response = postJson('/api/auth/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
    ]);

    $response->assertStatus(201)
        ->assertJson(['message' => __(Password::PASSWORD_RESET)]);

    // Verify password was actually changed
    $this->assertTrue(\Hash::check('NewPassword123!', $user->fresh()->password));
});

// Invalid token case
test('fails with invalid token', function () {
    $user = User::factory()->create();

    $response = postJson('/api/auth/reset-password', [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
    ]);

    $response->assertStatus(400)
        ->assertJson(['message' => __(Password::INVALID_TOKEN)]);
});

// Validation failures
test('validates input requirements', function (array $data, array $errors) {
    $response = postJson('/api/auth/reset-password', $data);
    $response->assertStatus(422)
        ->assertJsonValidationErrors($errors);
})->with([
    'missing token' => [
        ['email' => 'test@example.com', 'password' => 'password', 'password_confirmation' => 'password'],
        ['token']
    ],
    'invalid email' => [
        ['token' => 'token', 'email' => 'not-an-email', 'password' => 'password', 'password_confirmation' => 'password'],
        ['email']
    ],
    'short password' => [
        ['token' => 'token', 'email' => 'test@example.com', 'password' => 'short', 'password_confirmation' => 'short'],
        ['password']
    ],
    'mismatched passwords' => [
        ['token' => 'token', 'email' => 'test@example.com', 'password' => 'password', 'password_confirmation' => 'different'],
        ['password']
    ],
]);

// Non-existent user case
test('fails for non-existent email', function () {
    $response = postJson('/api/auth/reset-password', [
        'token' => 'any-token',
        'email' => 'missing@example.com',
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
    ]);

    $response->assertStatus(400)
        ->assertJson(['message' => __(Password::INVALID_USER)]);
});

// Server error handling
test('handles server errors', function () {
    $user = User::factory()->create();

    Password::shouldReceive('reset')
        ->once()
        ->andThrow(new \RuntimeException('Database error'));

    $response = postJson('/api/auth/reset-password', [
        'token' => 'any-token',
        'email' => $user->email,
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
    ]);

    $response->assertStatus(500)
        ->assertJson([
            'message' => 'Something went wrong',
            'error' => 'Database error'
        ]);
});
