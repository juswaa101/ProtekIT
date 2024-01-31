<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class PermissionPolicy
{
    /**
     * Determine whether the user can view all permissions.
     */
    public function view_permissions(User $user): bool
    {
        return $user->permissions()->where('permission_name', 'viewAnyPermissions')->exists();
    }

    /**
     * Determine whether the user can delete permission.
     */
    public function delete_permission(User $user): bool
    {
        return $user->permissions()->where('permission_name', 'deletePermission')->exists();
    }
}
