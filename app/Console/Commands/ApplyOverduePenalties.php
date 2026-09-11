<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\Notification;
use App\Models\Penalty;
use App\Models\PaymentSchedule;
use App\Models\SystemSetting;
use Illuminate\Console\Command;

class ApplyOverduePenalties extends Command
{
    protected $signature = 'loans:apply-penalties';

    protected $description = 'Detect overdue payment schedules and apply penalties per the configured policy';

    public function handle(): int
    {
        $ratePerDay = (float) SystemSetting::get('penalty_per_day', 20);
        $gracePeriodDays = (int) SystemSetting::get('grace_period_days', 0);
        $maxPenalty = (float) SystemSetting::get('max_penalty', 500);

        $schedules = PaymentSchedule::with('loan')
            ->whereIn('status', ['unpaid', 'partially_paid', 'overdue'])
            ->where('due_date', '<', now()->startOfDay())
            ->get();

        $updated = 0;

        foreach ($schedules as $schedule) {
            // absolute: true forces a positive day count regardless of direction —
            // Carbon 3 changed diffInDays() to return a signed value by default.
            $daysPastDue = now()->startOfDay()->diffInDays($schedule->due_date->copy()->startOfDay(), true);
            $effectiveDaysOverdue = max(0, $daysPastDue - $gracePeriodDays);

            if ($effectiveDaysOverdue <= 0) {
                continue; // still within grace period
            }

            $newPenalty = min(round($effectiveDaysOverdue * $ratePerDay, 2), $maxPenalty);
            $previousPenalty = (float) $schedule->penalty_amount;

            // Always ensure status reflects overdue, even if the penalty is already capped.
            if ($schedule->status === 'unpaid') {
                $schedule->status = 'overdue';
            }

            if ($newPenalty > $previousPenalty) {
                $increment = round($newPenalty - $previousPenalty, 2);
                $schedule->penalty_amount = $newPenalty;

                Penalty::create([
                    'payment_schedule_id' => $schedule->id,
                    'amount' => $increment,
                    'days_overdue' => $effectiveDaysOverdue,
                    'rate_per_day' => $ratePerDay,
                    'max_penalty_cap' => $maxPenalty,
                    'applied_at' => now(),
                ]);

                $isFirstPenalty = $previousPenalty == 0;

                Notification::create([
                    'user_id' => $schedule->loan->user_id,
                    'type' => $isFirstPenalty ? 'payment_overdue' : 'penalty_added',
                    'title' => $isFirstPenalty ? 'Payment Overdue' : 'Penalty Updated',
                    'message' => $isFirstPenalty
                        ? "Your installment for Month {$schedule->month_number} is overdue. A penalty of ₱{$increment} has been applied."
                        : "An additional penalty of ₱{$increment} has been applied to your overdue installment for Month {$schedule->month_number}.",
                ]);

                AuditLog::record(
                    null,
                    'Applied Overdue Penalty',
                    'PaymentSchedule',
                    $schedule->id,
                    "Days overdue: {$effectiveDaysOverdue}, Total penalty: ₱{$newPenalty}"
                );

                $updated++;
            }

            $schedule->save();
        }

        $this->info("Overdue detection complete. {$updated} schedule(s) updated.");

        return self::SUCCESS;
    }
}