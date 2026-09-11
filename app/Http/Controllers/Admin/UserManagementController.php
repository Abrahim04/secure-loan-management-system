<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::where('role', 'user')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->input('search') . '%')
                      ->orWhere('email', 'like', '%' . $request->input('search') . '%');
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->input('status') === 'active');
            })
            ->withCount(['loans', 'payments'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $user->update(['is_active' => ! $user->is_active]);

        AuditLog::record(
            auth()->id(),
            $user->is_active ? 'Activated User' : 'Blocked User',
            'User',
            $user->id,
            "User: {$user->name} ({$user->email})"
        );

        return back()->with('status', $user->is_active
            ? "{$user->name} has been reactivated."
            : "{$user->name} has been blocked.");
    }
}
