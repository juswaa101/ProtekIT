<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\UserRegisteredNotificationJob;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('pages.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'password_confirmation' => ['required', 'same:password'],
        ]);

        try {
            $user = User::create($request->except(['_token', 'password_confirmation']));

            // Assign role to user
            $user->roles()->attach(2);

            // Login user
            auth()->loginUsingId($user->id);

            // Send Welcome Notification to User
            dispatch(new UserRegisteredNotificationJob($user));

            return redirect()->route('home');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }
}
