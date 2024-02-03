<?php

namespace App\Notifications;

use App\Models\EventPlanner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventPlannerNotification extends Notification
{
    use Queueable;

    private EventPlanner $eventPlanner;
    private string $message;
    private string $title;


    /**
     * Create a new notification instance.
     */
    public function __construct(EventPlanner $eventPlanner, string $message, string $title)
    {
        $this->eventPlanner = $eventPlanner;
        $this->message = $message;
        $this->title = $title;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'user' => ''
        ];
    }
}
