<?php

namespace App\Notifications;

use App\Models\Tender;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TenderMatchedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $tender;

    public function __construct(Tender $tender)
    {
        $this->tender = $tender;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🔔 New Tender Matches Your Tags')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new tender matches one of your saved tags.')
            ->line('**Title:** ' . $this->tender->title)
            ->action('View Tender', url('/tenders/' . $this->tender->id))
            ->line('Thank you for using Tender Analyzer!');
    }
}
