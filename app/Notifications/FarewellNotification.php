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


    private readonly string $messageBody;
    private readonly string $subject;
    private readonly string $closer;
    #[ArrayShape(['address' => "string", 'name' => "string"])] private readonly array $from;

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
        $this->messageBody = "{$this->user->name}, we're sad to see you leave.";
        $this->from = ['address' => Config::get('mail.from.address'), 'name' => config('app.name')];
        $this->closer = sprintf("Thank you for using %s!", config('app.name'));
        $this->subject = "I guess it's goodbye for now...";
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
            ->from(...(list('address' => $address, 'name' => $name) = $this->from))
            ->line($this->messageBody)
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
            'closer' => $this->closer
        ];
    }
}
