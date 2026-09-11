<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Loan;
use App\Models\Notification;
use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoanReviewController extends Controller
{
    public function __construct(protected LoanService $loanService) {}

    public function index(): View
    {
        $loans = Loan::with(['user', 'loanType'])
            ->where('status', 'pending')
            ->oldest('applied_at')
            ->paginate(15);

        return view('admin.loans.index', compact('loans'));
    }

    public function show(Loan $loan): View
    {
        $loan->load(['user', 'loanType']);

        return view('admin.loans.show', compact('loan'));
    }

    public function approve(Request $request, Loan $loan): RedirectResponse
    {
        abort_unless($loan->status === 'pending', 400, 'Only pending loans can be approved.');

        DB::transaction(function () use ($loan, $request) {
            $loan->update([
                'status' => 'active',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            $this->loanService->generateSchedule($loan);

            Notification::create([
                'user_id' => $loan->user_id,
                'type' => 'loan_approved',
                'title' => 'Loan Approved',
                'message' => "Your loan application for ₱{$loan->principal_amount} has been approved. Your payment schedule is now available.",
            ]);

            AuditLog::record(auth()->id(), 'Approved Loan', 'Loan', $loan->id);
        });

        return redirect()->route('admin.loans.index')->with('status', 'Loan approved and payment schedule generated.');
    }

    public function reject(Request $request, Loan $loan): RedirectResponse
    {
        abort_unless($loan->status === 'pending', 400, 'Only pending loans can be rejected.');

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($loan, $validated) {
            $loan->update([
                'status' => 'rejected',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'rejection_reason' => $validated['rejection_reason'],
            ]);

            Notification::create([
                'user_id' => $loan->user_id,
                'type' => 'loan_rejected',
                'title' => 'Loan Application Rejected',
                'message' => "Your loan application was not approved. Reason: {$validated['rejection_reason']}",
            ]);

            AuditLog::record(auth()->id(), 'Rejected Loan', 'Loan', $loan->id, $validated['rejection_reason']);
        });

        return redirect()->route('admin.loans.index')->with('status', 'Loan rejected.');
    }
}
