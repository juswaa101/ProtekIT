<?php

namespace App\Jobs;

use App\Mail\SendPasswordLinkMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPasswordLinkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $email;

    /**
     * Create a new job instance.
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::where('email', $this->email)->firstOrFail();

        try {
            // Send email to user
            Mail::to($this->email)
                ->send(new SendPasswordLinkMail($user));
        } catch (\Exception $e) {
            Log::error('Failed to send password reset link: ' . $e->getMessage());
        }
    }
}
