<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view all users.
     */
    public function view_users(User $user): bool
    {
        return $user->permissions()->where('permission_name', 'viewAnyUsers')->exists();
    }

    /**
     * Determine whether the user can delete a user.
     */
    public function delete_user(User $user): bool
    {
        return $user->permissions()->where('permission_name', 'deleteUsers')->exists();
    }
}
