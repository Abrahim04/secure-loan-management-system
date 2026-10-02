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

    // Shows only pending applications that require review
    public function index(): View
    {
        $loans = Loan::with(['user', 'loanType'])
            ->where('status', 'pending')
            ->oldest('applied_at')
            ->paginate(15);

        return view('admin.loans.index', compact('loans'));
    }

    // NEW: Archive / History store for Approved, Active, and Rejected loans
    public function history(): View
    {
        $loans = Loan::with(['user', 'loanType', 'reviewer'])
            ->whereIn('status', ['active', 'approved', 'rejected', 'completed', 'paid'])
            ->latest('reviewed_at')
            ->paginate(15);

        return view('admin.loans.history', compact('loans'));
    }

    public function show(Loan $loan): View
    {
        $loan->load(['user', 'loanType']);

        return view('admin.loans.show', compact('loan'));
    }

    public function approve(Request $request, Loan $loan): RedirectResponse
    {
        abort_unless($loan->status === 'pending', 400, 'Only pending loans can be approved.');

        $applicantName = $loan->user->name ?? 'Applicant';

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
                'message' => "Your loan application for ₱" . number_format($loan->principal_amount, 2) . " has been approved. Your payment schedule is now available.",
            ]);

            AuditLog::record(auth()->id(), 'Approved Loan', 'Loan', $loan->id);
        });

        return redirect()->route('admin.loans.index')
            ->with('status', "Loan application for {$applicantName} has been approved and schedule generated.");
    }

    public function reject(Request $request, Loan $loan): RedirectResponse
    {
        abort_unless($loan->status === 'pending', 400, 'Only pending loans can be rejected.');

        $applicantName = $loan->user->name ?? 'Applicant';

        $validated = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $reason = $validated['rejection_reason'] ?? 'No specific reason provided.';

        DB::transaction(function () use ($loan, $reason) {
            $loan->update([
                'status' => 'rejected',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'rejection_reason' => $reason,
            ]);

            Notification::create([
                'user_id' => $loan->user_id,
                'type' => 'loan_rejected',
                'title' => 'Loan Application Rejected',
                'message' => "Your loan application was not approved. Reason: {$reason}",
            ]);

            AuditLog::record(auth()->id(), 'Rejected Loan', 'Loan', $loan->id, $reason);
        });

        return redirect()->route('admin.loans.index')
            ->with('status', "Loan application for {$applicantName} has been rejected.");
    }
}