<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\PaymentSchedule;
use Illuminate\Support\Carbon;

class LoanService
{
    /**
     * Flat-rate interest calculation, matching the plan's example:
     * Principal 20,000 + flat interest = Total Payable, split evenly across the term.
     *
     * @return array{interest_amount: float, total_payable: float, monthly_payment: float}
     */
    public function calculate(float $principal, float $interestRatePercent, int $termMonths): array
    {
        $interestAmount = round($principal * ($interestRatePercent / 100), 2);
        $totalPayable = round($principal + $interestAmount, 2);
        $monthlyPayment = round($totalPayable / $termMonths, 2);

        return [
            'interest_amount' => $interestAmount,
            'total_payable' => $totalPayable,
            'monthly_payment' => $monthlyPayment,
        ];
    }

    /**
     * Generate the monthly payment_schedules rows for an approved loan.
     * The final installment absorbs any rounding remainder so the schedule
     * sums exactly to total_payable.
     */
    public function generateSchedule(Loan $loan): void
    {
        $startDate = Carbon::now()->addMonthNoOverflow(); // first due date is one month out
        $allocated = 0.0;

        for ($month = 1; $month <= $loan->term_months; $month++) {
            $isLastInstallment = $month === $loan->term_months;

            $amountDue = $isLastInstallment
                ? round($loan->total_payable - $allocated, 2)
                : $loan->monthly_payment;

            $allocated += $amountDue;

            PaymentSchedule::create([
                'loan_id' => $loan->id,
                'month_number' => $month,
                'due_date' => $startDate->copy()->addMonthsNoOverflow($month - 1),
                'amount_due' => $amountDue,
                'amount_paid' => 0,
                'penalty_amount' => 0,
                'status' => 'unpaid',
            ]);
        }
    }
}
