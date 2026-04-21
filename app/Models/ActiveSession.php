<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActiveSession extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'user_agent',
        'browser_name',
        'browser_version',
        'operating_system',
        'device_type',
        'device_family',
        'device_fingerprint',
        'ip_address',
        'is_active',
        'logged_in_at',
        'last_seen_at',
        'logged_out_at',
        'active_duration_seconds',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'logged_in_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'logged_out_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
