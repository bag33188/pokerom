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

    private readonly string $farewellMessage;
    private readonly string $subject;
    private readonly string $salutation;
    #[ArrayShape(['address' => "string", 'name' => "string"])] private readonly array $from;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        self::$appName = preg_replace("/^Poke/", POKE_EACUTE, ucfirst(Config::get('app.name')));

        $this->setMailProps();
    }

    private function setMailProps(): void
    {
        $this->farewellMessage = "{$this->user->name}, we're sad to see you leave.";
        $this->from = ['address' => config('mail.from.address'), 'name' => self::$appName];
        $this->salutation = sprintf("Thank you for using %s!", self::$appName);
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
            ->from(...array_values($this->from))
            ->line($this->farewellMessage)
            ->line($this->salutation);
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
            'line1' => $this->farewellMessage,
            'line2' => $this->salutation
        ];
    }
}
