<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Get all users.
     */
    public function getUsers()
    {
        $this->authorize('view_users', User::class);

        $filter = request()->input('filter');

        $users = User::with(['roles', 'permissions'])
            ->when($filter, function ($query) use ($filter) {
                if ($filter === "all") {
                    return $query;
                } elseif ($filter === "archived") {
                    return $query->onlyTrashed();
                } else {
                    return $query->whereNull('deleted_at');
                }
            })
            ->where('id', '!=', auth()->user()->id)
            ->get();

        return response()->json(['users' => $users]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view_users', User::class);

        return view('pages.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete_user', User::class);

        try {
            // if soft delete from request exists
            if (request()->has('isSoftDelete')) {
                // If soft delete, then we will only mark the user as deleted.
                if (request()->input('isSoftDelete') && $user->deleted_at) {
                    $user->forceDelete();
                } else {
                    $user->deleteOrFail();
                }
            }

            return response()->json(['message' => 'User deleted.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Restore the specified resource from storage.
     */
    public function unarchiveUser(User $user)
    {
        $this->authorize('delete_user', User::class);

        try {
            $user->restore();

            return response()->json(['message' => 'User restored.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong.'], 500);
        }
    }
}
