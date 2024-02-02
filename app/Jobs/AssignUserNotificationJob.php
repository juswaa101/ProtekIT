<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\AssignUserNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AssignUserNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;
    private string $message;
    private string $title;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, string $title = '', string $message = '')
    {
        $this->user = $user;
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->user;

        $user->notify(
            new AssignUserNotification(
                $user,
                $this->title,
                $this->message
            )
        );
    }
}
