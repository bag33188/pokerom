<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use JetBrains\PhpStorm\ArrayShape;

class WelcomeNotification extends Notification
{
    use Queueable;

    public User $user;


    private readonly string $subject;
    private readonly string $messageBody;
    private readonly string $closer;
    #[ArrayShape(['address' => "string", 'name' => "string"])] private readonly array $from;
    #[ArrayShape(['text' => "string", 'url' => "string"])] private readonly array $action;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        $this->setMailProps();
    }

    private function setMailProps(): void
    {
        $this->subject = sprintf("Thank you for joining %s!!", config('app.name'));
        $this->messageBody = sprintf("Hello %s, welcome to the world of %s!", $this->user->name, config('app.name'));
        $this->from = ['address' => Config::get('mail.from.address'), 'name' => config('app.name')];
        $this->action = ['text' => 'Check it out!', 'url' => route('roms.index')];
        $this->closer = 'Enjoy!';
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
            ->subject($this->subject)
            ->from(...array_values($this->from))
            ->line($this->messageBody)
            ->action(...array_values($this->action))
            ->line($this->closer);
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
            'subject' => $this->subject,
            'from' => $this->from,
            'body' => $this->messageBody,
            'action' => $this->action,
            'closer' => $this->closer
        ];
    }
}
