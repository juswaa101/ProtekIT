<?php

namespace App\Jobs;

use App\Models\EventPlanner;
use App\Models\User;
use App\Notifications\EventPlannerNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EventPlannerNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private EventPlanner $eventPlanner;
    private string $message;
    private string $title;

    /**
     * Create a new job instance.
     */
    public function __construct(EventPlanner $eventPlanner, string $message, string $title)
    {
        $this->eventPlanner = $eventPlanner;
        $this->message = $message;
        $this->title = $title;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::all();

        // Send notification to all users
        $users->each(function ($user) {
            $user->notify(
                new EventPlannerNotification(
                    $this->eventPlanner,
                    $this->message,
                    $this->title
                )
            );
        });
    }
}
