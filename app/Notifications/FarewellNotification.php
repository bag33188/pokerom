<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use JetBrains\PhpStorm\ArrayShape;

class FarewellNotification extends Notification
{
    use Queueable;

    public User $user;
    private static string $appName;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        self::$appName = preg_replace("/^Poke/", POKE_EACUTE, ucfirst(Config::get('app.name')));

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
            ->from(config('mail.from.address'), self::$appName)
            ->line("{$this->user->name}, we're sad to see you leave.")
            ->line(sprintf("Thank you for using %s!", self::$appName));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    #[ArrayShape(['subject' => "string", 'from' => "\Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed", 'line1' => "string", 'line2' => "string"])]
    public function toArray(mixed $notifiable): array
    {
        return [
            'subject' => "I guess it's goodbye for now...",
            'from' => config('mail.from.address'),
            'line1' => "{$this->user->name}, we're sad to see you leave.",
            'line2' => 'Thank you for using ' . self::$appName . '!'
        ];
    }
}
