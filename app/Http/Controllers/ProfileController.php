<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show the user profile.
     *
     * @return \Illuminate\View\View
     */
    public function showProfile()
    {
        return view('pages.profile.index');
    }

    /**
     * Update the user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
        ]);

        try {
            $user = $request->user();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            return response()->json(['message' => 'Profile updated!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the profile.'], 500);
        }
    }

    /**
     * Update the user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string', 'min:8'],
            'new_password' => ['required', 'string', 'min:8'],
            'confirm_password' => ['required', 'string', 'min:8', 'same:new_password'],
        ]);

        try {
            $user = $request->user();

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'The provided password does not match your current password.'], 409);
            }

            $user->password = $request->new_password;
            $user->save();

            return response()->json(['message' => 'Password updated!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while updating the password.'], 500);
        }
    }
}
