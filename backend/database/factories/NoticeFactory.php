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
            'published_by' => $user->id,           // ID of user who published the notice
            'department_id' => $department->id, // Assigning the random department ID
            'published_on' => $this->faker->date(), // Optional: set published time
            'archived_on' => $this->faker->optional()->date(), // Optional: set archived time
            'file' => $this->faker->optional()->url(), // Optional: set file name (random)
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
