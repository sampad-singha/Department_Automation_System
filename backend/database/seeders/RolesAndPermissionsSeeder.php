<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissionsByCategory = [
            'Departments' => [
                'create departments',
                'delete departments',
                'force delete departments',
                'restore departments',
                'update departments',
                'view any departments',
                'view departments',
            ],
            'Permissions' => [
                'create permissions',
                'delete permissions',
                'force delete permissions',
                'restore permissions',
                'update permissions',
                'view any permissions',
                'view permissions',
            ],
            'Roles' => [
                'create roles',
                'delete roles',
                'force delete roles',
                'restore roles',
                'update roles',
                'view any roles',
                'view roles',
            ],
            'Users' => [
                'create users',
                'delete any users',
                'delete users',
                'update any users',
                'update users',
                'view any users',
                'view users',
            ],
            'Enrollments' => [
                'create enrollments',
                'delete enrollments',
                'force delete enrollments',
                'restore enrollments',
                'update enrollments',
                'view any enrollments',
                'view enrollments',
            ],
        ];
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($permissionsByCategory as $category => $permissions) {
            foreach ($permissions as $permission) {
                Permission::create(['name' => $permission, 'category' => $category]);
            }
        }

        //Create Roles
        Role::create(['name' => 'student']);
        Role::create(['name' => 'teacher']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'super-admin']);

        //Assign Permissions to Roles
        Role::findByName('student')->givePermissionTo([
            'view users',
            'view departments',
            'view any departments',
            'update users',
            'delete users',
        ]);
        Role::findByName('teacher')->givePermissionTo([
            'view users',
            'view any users',
            'update users',
            'delete users',
            'view departments',
            'view any departments',
        ]);
        Role::findByName('admin')->givePermissionTo([
            'create users',
            'delete any users',
            'delete users',
            'update any users',
            'update users',
            'view any users',
            'view users',
            'create departments',
            'delete departments',
            'force delete departments',
            'restore departments',
            'update departments',
            'view any departments',
            'view departments',
            //temporarily added
            'create permissions',
            'delete permissions',
            'force delete permissions',
            'restore permissions',
            'update permissions',
            'view any permissions',
            'view permissions',
            'create roles',
            'delete roles',
            'force delete roles',
            'restore roles',
            'update roles',
            'view any roles',
            'view roles',
        ]);
        Role::findByName('super-admin')->givePermissionTo(Permission::all());

    }
}
