<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //add student role to all users when seeding
        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('student');
        });

        User::factory(5)->create()->each(function ($user) {
            $user->assignRole('teacher');
        });

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
            'city' => 'Dhaka',
            'department_id' => 1,
            'dob' => '1995-01-01',
            'phone' => '01712345678',
            'university_id' => 123459,
            'publication_count' => 4,
        ]);
        $admin = Role::findByName('admin');
        $adminUser->assignRole($admin);

        $superAdminUser = User::factory()->create([
            'name' => 'Super Admin',
            'image' => fake()->imageUrl(),
            'email' => 'superadmin@example.com',
            'designation' => 'staff',
            'password' => Hash::make('password'),
            'status' => 'active',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'city' => 'Pabna',
            'department_id' => 1,
            'dob' => '1986-03-01',
            'phone' => '01712344563',
            'university_id' => 123456,
            'publication_count' => 4,
        ]);
        $superAdmin = Role::findByName('super-admin');
        $superAdminUser->assignRole($superAdmin);
    }
}
