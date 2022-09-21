<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use JetBrains\PhpStorm\ArrayShape;

class FarewellNotification extends Notification
{
    use Queueable;

    public User $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("I guess it's goodbye for now...")
            ->line("{$this->user->name}, we're sad to see you leave.")
            ->line(sprintf("Thank you for using %s!", config('app.name')));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    #[ArrayShape(['subject' => "string", 'line1' => "string", 'line2' => "string"])]
    public function toArray(mixed $notifiable): array
    {
        return [
            'subject' => "I guess it's goodbye for now...",
            'line1' => "{$this->user->name}, we're sad to see you leave.",
            'line2' => 'Thank you for using ' . config('app.name') . '!'
        ];
    }
}
