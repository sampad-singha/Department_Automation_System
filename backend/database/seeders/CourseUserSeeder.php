<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();  // Get all users
        $courses = Course::all();  // Get all courses

        // Ensure there are users and courses
        if ($users->isEmpty() || $courses->isEmpty()) {
            $this->command->warn('No users or courses found. Skipping CourseUserSeeder.');
            return;
        }

        // Attach users to random courses
        foreach ($users as $user) {
            $randomCourses = $courses->random(rand(1, 5))->pluck('id'); // Assign 1 to 5 courses
            $user->courses()->attach($randomCourses);
        }
    }
}
