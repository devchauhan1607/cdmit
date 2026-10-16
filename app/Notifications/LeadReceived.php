<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Lead $lead) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New CDMIT inquiry from {$this->lead->name}")
            ->greeting('New project inquiry')
            ->line("{$this->lead->name} submitted a new inquiry.")
            ->line("Email: {$this->lead->email}")
            ->line('Company: '.($this->lead->company ?: 'Not provided'))
            ->line('Service: '.($this->lead->service?->title ?: 'Not selected'))
            ->line($this->lead->message)
            ->action('Review lead', route('admin.leads.show', $this->lead))
            ->replyTo($this->lead->email, $this->lead->name);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
