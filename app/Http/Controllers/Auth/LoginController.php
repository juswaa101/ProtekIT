<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'min:8'],
        ]);

        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            return redirect()->route('home');
        }

        return redirect()->back()
            ->withInput($request->only('email'))
            ->with(
                'error',
                'The provided credentials do not match our records.'
            );
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->route('login');
    }
}
