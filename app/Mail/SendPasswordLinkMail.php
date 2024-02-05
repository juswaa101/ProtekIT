<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class SendPasswordLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    private User $user;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the password reset link for the given user.
     */
    public function build()
    {
        $user = $this->user;
        $url = URL::temporarySignedRoute(
            'password.reset',
            now()->addMinutes(30),
            ['user' => $user->id]
        );

        return $this->markdown('mail.password-link-email')
            ->subject('Reset your password')
            ->with([
                'user' => $user,
                'url' => $url,
            ]);
    }
}
