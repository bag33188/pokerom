<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class WelcomeNotification extends Notification
{
    use Queueable;

    public User $user;
    private static string $appName;
    private string $welcomeMessage;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        self::$appName = preg_replace("/^Poke/", POKE_EACUTE, ucfirst(Config::get('app.name')));
        $this->welcomeMessage = sprintf("Hello %s, welcome to the world of %s!", $this->user->name, self::$appName);
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
            ->subject('Thank you for joining ' . self::$appName . '!!')
            ->from(config('mail.from.address'), self::$appName)
            ->line($this->welcomeMessage)
            ->action('Check it out!', route('roms.index'))
            ->line('Enjoy!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'subject' => 'Thank you for joining ' . self::$appName . '!!',
            'from' => [config('mail.from.address'), self::$appName],
            'line1' => $this->welcomeMessage,
            'action' => [
                'actionText' => 'Check it out!',
                'actionUrl' => route('roms.index')
            ],
            'line2' => 'Enjoy!'
        ];
    }
}
