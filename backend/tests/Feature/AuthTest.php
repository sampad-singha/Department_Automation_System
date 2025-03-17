<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful login for a regular user.
     *
     * @return void
     */
    public function test_successful_login_for_regular_user()
    {
        // Create a regular user
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Attempt to log in
        $response = $this->postJson('/api/auth/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        // Assert the response status is 200 (OK)
        $response->assertStatus(200);

        // Assert the response contains a token and user data
        $response->assertJsonStructure([
            'token',
            'user' => [
                'id',
                'name',
                'email',
                // Add other user attributes as needed
            ],
        ]);
    }

    /**
     * Test login attempt with invalid credentials.
     *
     * @return void
     */
    public function test_login_with_invalid_credentials()
    {
        // Create a user
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Attempt to log in with incorrect password
        $response = $this->postJson('/api/auth/login', [
            'email' => 'user@example.com',
            'password' => 'wrongpassword',
        ]);

        // Assert the response status is 401 (Unauthorized)
        $response->assertStatus(401);

        // Assert the response contains the appropriate error message
        $response->assertJson([
            'message' => 'Invalid credentials',
        ]);
    }

    /**
     * Test login attempt by an admin or super-admin user.
     *
     * @return void
     */
    public function test_admin_or_super_admin_login_attempt()
    {
        // Create the 'admin' role
        $adminRole = Role::create(['name' => 'admin']);
        // Create an admin user
        $adminUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Assign the 'admin' role to the user
        $adminUser->assignRole($adminRole);

        // Attempt to log in
        $response = $this->postJson('/api/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        // Assert the response status is 403 (Forbidden)
        $response->assertStatus(403);

        // Assert the response contains the appropriate error message
        $response->assertJson([
            'message' => 'Admins cannot log in here',
        ]);
    }
}
