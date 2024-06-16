<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeadlineNotification extends Notification
{
    use Queueable;
    protected $todo;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($todo, $message)
    {
        $this->todo = $todo;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->line($this->message)
        ->action('View Task', url('/todo/' . $this->todo->id))
        ->line('Terima Kasih telah menggunakan web apps saya!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
