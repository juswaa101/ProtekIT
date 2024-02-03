<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed all default permissions
        Permission::create([
            'permission_name' => 'viewAnyUsers',
            'display_name' => 'View Users'
        ]);

        Permission::create([
            'permission_name' => 'viewAnyPermissions',
            'display_name' => 'View Permissions'
        ]);

        Permission::create([
            'permission_name' => 'viewAnyRoles',
            'display_name' => 'View Roles'
        ]);

        Permission::create([
            'permission_name' => 'createRoles',
            'display_name' => 'Create Role'
        ]);

        Permission::create([
            'permission_name' => 'updateRoles',
            'display_name' => 'Update Role'
        ]);

        Permission::create([
            'permission_name' => 'deleteRoles',
            'display_name' => 'Delete Role'
        ]);

        Permission::create([
            'permission_name' => 'deleteUsers',
            'display_name' => 'Delete User'
        ]);

        Permission::create([
            'permission_name' => 'deletePermission',
            'display_name' => 'Delete Permission'
        ]);

        Permission::create([
            'permission_name' => 'viewAnyEventPlanners',
            'display_name' => 'View Event Planners'
        ]);

        Permission::create([
            'permission_name' => 'createEventPlanners',
            'display_name' => 'Create Event Planner'
        ]);

        Permission::create([
            'permission_name' => 'deleteEventPlanners',
            'display_name' => 'Delete Event Planner'
        ]);
    }
}
