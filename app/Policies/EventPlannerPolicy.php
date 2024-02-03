<?php

namespace App\Policies;

use App\Models\EventPlanner;
use App\Models\User;

class EventPlannerPolicy
{
    /**
     * Determine whether the user can view any events planned.
     */
    public function view_events(User $user)
    {
        return $user->permissions()->where('permission_name', 'viewAnyEventPlanners')->exists();
    }

    /**
     * Determine whether the user can create a event.
     */
    public function create_events(User $user)
    {
        return $user->permissions()->where('permission_name', 'createEventPlanners')->exists();
    }

    /**
     * Determine whether the user can delete the event.
     */
    public function delete_events(User $user)
    {
        return $user->permissions()->where('permission_name', 'deleteEventPlanners')->exists();
    }

    /**
     * Determine whether the user can delete own event.
     */
    public function delete_own_events(User $user)
    {
        return $user->events()->where('user_id', $user->id)->exists();
    }
}
