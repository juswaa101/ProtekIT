<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendPasswordLinkJob;
use App\Models\User;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /**
     * Display the password reset link request form.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('pages.auth.forgot-password');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        try {
            dispatch(new SendPasswordLinkJob($request->email));

            return response()->json([
                'message' => 'Password reset link sent to your email',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send reset link',
            ], 500);
        }
    }

    /**
     * Display the password reset form.
     *
     * @return \Illuminate\View\View
     */
    public function showResetForm()
    {
        return view('pages.auth.reset-password');
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'new_password' => ['required', 'string', 'min:8'],
            'new_confirm_password' => ['required', 'string', 'min:8', 'same:new_password'],
        ]);

        try {
            $user = User::findOrFail($request->user);

            $user->update([
                'password' => $request->new_password,
            ]);

            return response()->json([
                'message' => 'Password reset successful',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to reset password',
            ], 500);
        }
    }
}
