<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\SiteSetting;
use App\Notifications\LeadReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Throwable;

class SendLeadNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public array $backoff = [60, 300, 900];

    public function __construct(public Lead $lead) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $recipient = SiteSetting::value('lead_notification_email', config('cdmit.lead_notification_email'));

        if (! $recipient) {
            $this->lead->update([
                'notification_status' => 'skipped',
                'notification_error' => 'No lead notification recipient is configured.',
            ]);

            return;
        }

        try {
            $this->lead->loadMissing('service');
            Notification::route('mail', $recipient)->notify(new LeadReceived($this->lead));
            $this->lead->update([
                'notification_status' => 'sent',
                'notification_error' => null,
                'notification_sent_at' => now(),
            ]);
        } catch (Throwable $exception) {
            $this->lead->update([
                'notification_status' => 'failed',
                'notification_error' => mb_substr($exception->getMessage(), 0, 2000),
            ]);
            Log::error('Lead notification failed', ['lead_id' => $this->lead->id, 'exception' => $exception]);
            throw $exception;
        }
    }
}
