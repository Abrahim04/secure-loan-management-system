<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Mfa extends Model
{
    use HasFactory;

    protected $table = 'mfa';

    protected $fillable = [
        'user_id',
        'otp_code',
        'expires_at',
        'attempts',
        'is_used',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'is_used' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return ! $this->is_used && ! $this->isExpired();
    }

    /**
     * Generate and store a fresh 6-digit OTP for a user, invalidating any previous ones.
     */
    public static function generateFor(User $user, int $expiryMinutes = 5): self
    {
        static::where('user_id', $user->id)
            ->where('is_used', false)
            ->update(['is_used' => true]); // invalidate previous OTPs

        return static::create([
            'user_id' => $user->id,
            'otp_code' => str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'expires_at' => Carbon::now()->addMinutes($expiryMinutes),
            'attempts' => 0,
            'is_used' => false,
        ]);
    }
}
