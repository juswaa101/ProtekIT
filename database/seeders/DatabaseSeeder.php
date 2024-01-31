<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed permissions, roles and users
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class
        ]);

        // Create admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => 'password'
        ]);

        // Assign role to admin
        $admin->roles()->attach(1);

        // Assign all permissions to admin
        $permissions = Permission::all();
        $admin->permissions()->attach($permissions);
    }
}
