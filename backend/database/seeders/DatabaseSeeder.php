<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create(['name' => 'student']);


        $this->call([
            DepartmentSeeder::class,
            UserSeeder::class,
            CourseSeeder::class,
            CourseUserSeeder::class,
            NoticeSeeder::class,
        ]);
        $adminUser = User::factory()->create([
            'name' => 'Admin User 1',
            'image' => fake()->imageUrl(),
            'email' => 'admin@example.com',
            'designation' => 'staff',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'address' => 'Noorjahan Road, Mohammadpur',
            'city' => 'Dhaka',
            'department_id' => 1,
            'dob' => '1995-01-01',
            'phone' => '01712345678',
            'session' => '2015',
            'year' => 4,
            'semester' => 2,
            'university_id' => 123456,
            'publication_count' => 4,
        ]);
        $role = Role::create(['name' => 'admin']);
        $adminUser->assignRole($role);
    }
}
