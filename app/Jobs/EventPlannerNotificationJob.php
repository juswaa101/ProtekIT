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
    private string $url;

    /**
     * Create a new job instance.
     */
    public function __construct(EventPlanner $eventPlanner, string $message, string $title, string $url = '/home')
    {
        $this->eventPlanner = $eventPlanner;
        $this->message = $message;
        $this->title = $title;
        $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::whereHas('permissions', function ($query) {
            $query->where('permission_name', 'viewAnyEventPlanners');
        })->get();

        // Send notification to users with event planner access
        $users->each(function ($user) {
            $user->notify(
                new EventPlannerNotification(
                    $this->eventPlanner,
                    $this->message,
                    $this->title,
                    $this->url
                )
            );
        });
    }
}
