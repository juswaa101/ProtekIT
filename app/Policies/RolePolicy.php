<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    /**
     * Determine whether the user can view all roles.
     */
    public function view_roles(User $user): bool
    {
        return $user->permissions()->where('permission_name', 'viewAnyRoles')->exists();
    }

    /**
     * Determine whether the user can create roles.
     */
    public function create_role(User $user): bool
    {
        return $user->permissions()->where('permission_name', 'createRoles')->exists();
    }

    /**
     * Determine whether the user can update the role.
     */
    public function update_role(User $user): bool
    {
        return $user->permissions()->where('permission_name', 'updateRoles')->exists();
    }

    /**
     * Determine whether the user can delete the role.
     */
    public function delete_role(User $user): bool
    {
        return $user->permissions()->where('permission_name', 'deleteRoles')->exists();
    }
}
