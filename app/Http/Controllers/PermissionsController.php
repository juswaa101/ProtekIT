<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    /**
     * Get all permissions.
     */
    public function getPermissions()
    {
        $this->authorize('view_permissions', Permission::class);

        return response()->json([
            'permissions' => Permission::with('users')->get(),
        ]);
    }

    /**
     * Get all users.
     */
    public function getUsers()
    {
        $this->authorize('view_permissions', Permission::class);

        return response()->json([
            'users' => User::with(['roles', 'permissions'])
                ->where('id', '!=', auth()->user()->id)
                ->get(),
        ]);
    }

    /**
     * Show current user permissions.
     */
    public function showUserPermissions(User $user)
    {
        $this->authorize('view_permissions', Permission::class);

        return response([
            'permissions' => User::with(['permissions'])
                ->where('id', $user->id)
                ->firstOrFail(),
        ]);
    }


    /**
     * Assign permissions to user.
     */
    public function assignPermissions(Request $request)
    {
        $this->authorize('view_permissions', Permission::class);

        $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['required', 'integer'],
        ]);

        $user = User::findOrFail($request->user_id);
        try {
            $user->permissions()->sync($request->permissions);

            return response()->json([
                'message' => 'Permissions assigned successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.',
            ], 500);
        }
    }

    /**
     * Show permission assign page.
     */
    public function assignPage()
    {
        $this->authorize('view_permissions', Permission::class);

        return view('pages.permissions.assign');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view_permissions', Permission::class);

        return view('pages.permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $this->authorize('delete_permission', Permission::class);

        try {
            if ($permission->users()->count() > 0) {
                return response()->json([
                    'message' => 'Permission is assigned to user(s).',
                ], 409);
            }

            $permission->delete();

            return response()->json([
                'message' => 'Permission deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong.'
            ], 500);
        }
    }
}
