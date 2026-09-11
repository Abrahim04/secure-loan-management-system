<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityEvent extends Model
{
    use HasFactory;

    public $timestamps = true;
    const UPDATED_AT = null; // append-only

    protected $fillable = [
        'user_id',
        'event_type',
        'description',
        'ip_address',
        'user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Convenience helper, e.g. SecurityEvent::record($user?->id, 'failed_login', 'Invalid password');
     */
    public static function record(?int $userId, string $eventType, ?string $description = null): self
    {
        return static::create([
            'user_id' => $userId,
            'event_type' => $eventType,
            'description' => $description,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}
