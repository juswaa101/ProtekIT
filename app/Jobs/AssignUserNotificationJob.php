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
    private string $url;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, string $title = '', string $message = '', string $url = '/home')
    {
        $this->user = $user;
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
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
                $this->message,
                $this->url
            )
        );
    }
}
