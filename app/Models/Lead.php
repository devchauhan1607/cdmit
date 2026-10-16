<?php

namespace App\Models;

use App\Enums\LeadStatus;
use Database\Factories\LeadFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    /** @use HasFactory<LeadFactory> */
    use HasFactory;

    protected $fillable = [
        'service_id', 'name', 'email', 'company', 'country', 'phone', 'budget', 'message',
        'consent', 'status', 'source', 'duplicate_hash', 'ip_hash', 'user_agent',
        'notification_status', 'notification_error', 'notification_sent_at', 'last_contacted_at',
    ];

    protected $hidden = ['ip_hash', 'duplicate_hash'];

    protected function casts(): array
    {
        return [
            'consent' => 'boolean', 'status' => LeadStatus::class,
            'notification_sent_at' => 'datetime', 'last_contacted_at' => 'datetime',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(LeadNote::class)->latest();
    }
}
