<?php

return [
    'lead_notification_email' => env('LEAD_NOTIFICATION_EMAIL'),
    'lead_statuses' => ['new', 'contacted', 'qualified', 'proposal_sent', 'won', 'lost'],
    'publication_statuses' => ['draft', 'published', 'archived'],
];
