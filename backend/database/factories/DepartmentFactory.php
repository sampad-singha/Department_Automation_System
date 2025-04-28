<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Define full names and corresponding short codes
        $departments = [
            'Computer Science' => 'CS',
            'Mathematics' => 'MATH',
            'Physics' => 'PHY',
            'Chemistry' => 'CHEM',
            'Biology' => 'BIO',
            'Geology' => 'GEO',
            'Geography' => 'GEOG',
            'Economics' => 'ECO',
            'Accounting' => 'ACC',
            'Business Administration' => 'BA',
            'Political Science' => 'PS',
            'Sociology' => 'SOC',
            'Psychology' => 'PSY',
            'History' => 'HIS',
            'Philosophy' => 'PHIL',
            'Religious Studies' => 'RS',
            'English' => 'ENG',
            'French' => 'FRE',
            'Spanish' => 'SPA',
            'German' => 'GER',
            'Arabic' => 'ARB',
        ];
        // Convert to an indexed array to maintain order
        $keys = array_keys($departments);

        // Select a random department ensuring uniqueness
        $name = $this->faker->unique()->randomElement($keys);

        // Get the corresponding short name (code)
        $shortName = $departments[$name];
        return [
            'name' => $name,
            'faculty' => $this->faker->name,
            'short_name' => $shortName,
        ];
    }
}
