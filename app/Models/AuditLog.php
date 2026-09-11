<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = true;
    const UPDATED_AT = null; // audit logs are append-only, no updates

    protected $fillable = [
        'user_id',
        'action',
        'related_type',
        'related_id',
        'description',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Convenience helper to record an audit entry.
     */
    public static function record(?int $userId, string $action, ?string $relatedType = null, ?int $relatedId = null, ?string $description = null): self
    {
        return static::create([
            'user_id' => $userId,
            'action' => $action,
            'related_type' => $relatedType,
            'related_id' => $relatedId,
            'description' => $description,
            'ip_address' => request()?->ip(),
        ]);
    }
}
