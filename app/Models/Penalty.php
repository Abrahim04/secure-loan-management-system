<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penalty extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_schedule_id',
        'amount',
        'days_overdue',
        'rate_per_day',
        'max_penalty_cap',
        'applied_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'rate_per_day' => 'decimal:2',
            'max_penalty_cap' => 'decimal:2',
            'applied_at' => 'datetime',
        ];
    }

    public function paymentSchedule(): BelongsTo
    {
        return $this->belongsTo(PaymentSchedule::class);
    }
}
