<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notice>
 */
class NoticeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get a random user and department for the foreign keys
        $user = User::inRandomOrder()->first();
        $departments = Department::all();
        $department = $departments->random();

        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'user_id' => $user->id,           // Assigning the random user ID
            'department_id' => $department->id, // Assigning the random department ID
            'published_at' => $this->faker->dateTimeThisYear(), // Optional: set published time
            'archived_at' => $this->faker->optional()->dateTimeThisYear(), // Optional: set archived time
            'file' => $this->faker->optional()->url(), // Optional: set file name (random)
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
