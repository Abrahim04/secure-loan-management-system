<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $showingTrash = $request->boolean('trashed');

        $query = $showingTrash
            ? User::onlyTrashed()->where('role', 'user')
            : User::where('role', 'user');

        $users = $query
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('name', 'like', '%' . $request->input('search') . '%')
                        ->orWhere('email', 'like', '%' . $request->input('search') . '%');
                });
            })
            ->when(! $showingTrash && $request->filled('status'), function ($q) use ($request) {
                $q->where('is_active', $request->input('status') === 'active');
            })
            ->withCount(['loans', 'payments'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'showingTrash'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:admin,user'],
            'verification_status' => ['required', 'in:verified,unverified'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
            'email_verified_at' => $validated['verification_status'] === 'verified' ? now() : null,
        ]);

        AuditLog::record(auth()->id(), 'Created Borrower', 'User', $user->id, "Created {$user->name} ({$user->email}) with role {$user->role}");

        return back()->with('status', "Borrower \"{$user->name}\" created successfully.");
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,user'],
            'status' => ['required', 'in:active,blocked'],
            'verification_status' => ['required', 'in:verified,unverified'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => $validated['status'] === 'active',
            'email_verified_at' => $validated['verification_status'] === 'verified'
                ? ($user->email_verified_at ?? now())
                : null,
        ]);

        AuditLog::record(auth()->id(), 'Updated Borrower', 'User', $user->id, "Updated {$user->name} ({$user->email})");

        return back()->with('status', "Borrower \"{$user->name}\" updated successfully.");
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $user->update(['is_active' => ! $user->is_active]);

        AuditLog::record(
            auth()->id(),
            $user->is_active ? 'Activated Borrower' : 'Blocked Borrower',
            'User',
            $user->id,
            "Borrower: {$user->name} ({$user->email})"
        );

        return back()->with('status', $user->is_active
            ? "Borrower \"{$user->name}\" has been reactivated."
            : "Borrower \"{$user->name}\" has been blocked.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account while logged in as it.');
        }

        $name = $user->name;
        $user->delete(); // soft delete — sets deleted_at

        AuditLog::record(auth()->id(), 'Deleted Borrower (soft)', 'User', $user->id, "Moved {$name} to trash");

        return back()->with('status', "Borrower \"{$name}\" moved to trash successfully.");
    }

    public function restore(int $id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        AuditLog::record(auth()->id(), 'Restored Borrower', 'User', $user->id, "Restored {$user->name} ({$user->email}) from trash");

        return back()->with('status', "Borrower \"{$user->name}\" restored successfully.");
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);

        // Refuse to cascade-delete a borrower's financial history through a UI click.
        if ($user->loans()->exists()) {
            return back()->with('error', "Cannot permanently delete \"{$user->name}\" because financial loan records exist for this borrower. Restore the account instead if it needs to remain reachable, or handle the loan records first.");
        }

        $name = $user->name;
        $email = $user->email;
        $user->forceDelete();

        // Record against the acting admin, not the now-deleted borrower.
        AuditLog::record(auth()->id(), 'Permanently Deleted Borrower', 'User', null, "Permanently deleted {$name} ({$email})");

        return back()->with('status', "Borrower \"{$name}\" permanently deleted.");
    }
}
