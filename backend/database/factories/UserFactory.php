<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'image' => $this->faker->imageUrl(),
            'university_id' => $this->faker->unique()->randomNumber(),
            'session' => $this->faker->year(),
            'dob' => $this->faker->date(),
            'phone' => $this->faker->numerify('01########'), // BD phone format
            'address' => $this->faker->address,
            'year' => $this->faker->numberBetween(1, 4),
            'semester' => $this->faker->numberBetween(1, 2),
            'designation' => $this->faker->randomElement(['student', 'teacher', 'staff']),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'city' => $this->faker->randomElement(['Dhaka', 'Chittagong', 'Rajshahi', 'Khulna', 'Sylhet']),
            'department_id' => Department::query()->inRandomOrder()->first()->id ?? Department::factory(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
