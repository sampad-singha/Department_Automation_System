<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesandPermissionsSeeder::class,
            DepartmentSeeder::class,
            UserSeeder::class,
            CourseSeeder::class,
            CourseUserSeeder::class,
            NoticeSeeder::class,
        ]);


    }
}
