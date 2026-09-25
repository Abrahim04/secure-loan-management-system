<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Loan;
use App\Models\LoanType;
use App\Models\Notification;
use App\Models\User;
use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function __construct(protected LoanService $loanService) {}

    public function index(): View
    {
        $loans = auth()->user()->loans()->with('loanType')->latest()->paginate(10);

        return view('loans.index', compact('loans'));
    }

    public function create(): View
    {
        $loanTypes = LoanType::where('is_active', true)->get();

        return view('loans.create', compact('loanTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $loanType = LoanType::findOrFail($request->input('loan_type_id'));

        $validated = $request->validate([
            'loan_type_id' => ['required', 'exists:loan_types,id'],
            'principal_amount' => [
                'required', 'numeric',
                'min:' . $loanType->min_amount,
                'max:' . $loanType->max_amount,
            ],
            'term_months' => [
                'required', 'integer',
                'min:' . $loanType->min_term_months,
                'max:' . $loanType->max_term_months,
            ],
            'purpose' => ['nullable', 'string', 'max:1000'],
        ]);

        $calc = $this->loanService->calculate(
            (float) $validated['principal_amount'],
            (float) $loanType->interest_rate,
            (int) $validated['term_months']
        );

        $loan = Loan::create([
            'user_id' => auth()->id(),
            'loan_type_id' => $loanType->id,
            'principal_amount' => $validated['principal_amount'],
            'interest_amount' => $calc['interest_amount'],
            'total_payable' => $calc['total_payable'],
            'term_months' => $validated['term_months'],
            'monthly_payment' => $calc['monthly_payment'],
            'status' => 'pending',
            'purpose' => $validated['purpose'] ?? null,
            'applied_at' => now(),
        ]);

        AuditLog::record(auth()->id(), 'Submitted Loan Application', 'Loan', $loan->id);

        // Magpadala ng notification sa lahat ng Admin (Isinama ang 'type')
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'loan',
                'title' => 'New Loan Application',
                'message' => auth()->user()->name . ' applied for a ' . $loanType->name . ' amounting to ₱' . number_format($loan->principal_amount, 2) . '.',
                'is_read' => false,
            ]);
        }

        return redirect()->route('loans.show', $loan)
            ->with('status', 'Your loan application has been submitted and is pending review.');
    }

    public function show(Loan $loan): View
    {
        abort_unless($loan->user_id === auth()->id(), 403);

        $loan->load(['loanType', 'paymentSchedules']);

        return view('loans.show', compact('loan'));
    }
}