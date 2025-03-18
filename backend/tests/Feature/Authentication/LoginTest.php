<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\{postJson};
use Illuminate\Foundation\Testing\RefreshDatabase;

// Apply RefreshDatabase trait to all tests in this file
uses(RefreshDatabase::class);

beforeEach(function () {
    // Set up common test data or state here
    $this->user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => Hash::make('password123'),
    ]);
});

it('allows a regular user to log in successfully', function () {
    $response = postJson('/api/auth/login', [
        'email' => 'user@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'token',
            'user' => [
                'id',
                'name',
                'email',
                'image',
                'university_id',
                'department_id',
                'session',
                'year',
                'semester',
                'dob',
                'phone',
                'address',
                'city',
                'designation',
                'publication_count',
            ],
        ]);
});

it('denies login with invalid credentials', function () {
    $response = postJson('/api/auth/login', [
        'email' => 'user@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'message' => 'Invalid credentials',
        ]);
});

it('denies login for admin users', function () {
    // Create the 'admin' role
    $adminRole = Role::create(['name' => 'admin']);
    // Assign the 'admin' role to the user
    $this->user->assignRole($adminRole);

    $response = postJson('/api/auth/login', [
        'email' => 'user@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(403)
        ->assertJson([
            'message' => 'Admins cannot log in here',
        ]);
});
