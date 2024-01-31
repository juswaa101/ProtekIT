<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(20)->create();

        // Get all users created
        $users = User::all();

        // Attach user role to all users
        foreach ($users as $user) {
            $user->roles()->attach(2);
        }
    }
}
