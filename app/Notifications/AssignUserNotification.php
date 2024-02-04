<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignUserNotification extends Notification
{
    use Queueable;

    private User $user;
    private string $message;
    private string $title;
    private string $url;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, string $title = '', string $message = '', string $url = '/home')
    {
        $this->user = $user;
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
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
            'user' => $this->user,
            'url' => $this->url
        ];
    }
}
