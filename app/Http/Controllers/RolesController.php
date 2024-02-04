<?php

namespace App\Http\Controllers;

use App\Jobs\AssignUserNotificationJob;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Get all roles.
     */
    public function getRoles()
    {
        $this->authorize('view_roles', Role::class);

        $roles = Role::with('users')->get();

        return response()->json(['roles' => $roles]);
    }

    /**
     * Show permission assign page.
     */
    public function assignPage()
    {
        $this->authorize('view_roles', Role::class);

        return view('pages.roles.assign');
    }

    /**
     * Get all users.
     */
    public function getUsers()
    {
        $this->authorize('view_roles', Role::class);

        return response()->json([
            'users' => User::with(['roles'])
                ->where('id', '!=', auth()->user()->id)
                ->get(),
        ]);
    }

    /**
     * Show current user roles.
     */
    public function showUserRoles(User $user)
    {
        $this->authorize('view_roles', Role::class);

        return response([
            'user' => User::with(['roles'])
                ->where('id', $user->id)
                ->firstOrFail(),
        ]);
    }

    /**
     * Assign roles to user.
     */
    public function assignRoles(Request $request)
    {
        $this->authorize('view_roles', Role::class);

        $request->validate([
            'roles' => ['required', 'array'],
            'roles.*' => ['required', 'integer'],
        ]);

        $user = User::findOrFail($request->user_id);
        try {
            $user->roles()->sync($request->roles);

            dispatch(
                new AssignUserNotificationJob(
                    $user,
                    'Roles updated.',
                    'Your roles have been updated.',
                    route('profile.index')
                )
            );

            return response()->json([
                'message' => 'Roles assigned successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view_roles', Role::class);

        return view('pages.roles.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create_role', Role::class);

        $request->validate(['name' => ['required', 'unique:roles,name']]);

        try {
            Role::create(['name' => $request->name]);

            return response()->json(['message' => 'Role created.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $this->authorize('update_role', Role::class);

        return response()->json(['role' => $role], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $this->authorize('update_role', Role::class);

        $request->validate(['name' => ['required', 'unique:roles,name,' . $role->id . ',id']]);

        try {
            $role->update(['name' => $request->name]);

            return response()->json(['message' => 'Role updated.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete_role', Role::class);

        try {
            if ($role->users()->exists()) {
                return response()->json(['message' => 'Role is in use.'], 409);
            }

            $role->deleteOrFail();

            return response()->json(['message' => 'Role deleted.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong.'], 500);
        }
    }
}
