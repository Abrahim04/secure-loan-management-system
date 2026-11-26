<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationManagementController extends Controller
{
    public function index(Request $request): View
    {
        // Kunin ang mga users na may status na 'pending' o naka-filter
        $status = $request->input('status', 'pending');

        $query = User::where('role', 'user');

        if ($status !== 'all') {
            $query->where('verification_status', $status);
        } else {
            $query->whereIn('verification_status', ['pending', 'verified', 'unverified']);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.verifications.index', compact('users', 'status'));
    }

    public function approve(User $user): RedirectResponse
    {
        $user->update([
            'verification_status' => 'verified',
        ]);

        // Magdagdag ng notification sa inbox ng borrower kapag na-approve
        Notification::create([
            'user_id' => $user->id,
            'type' => 'verification_approved',
            'title' => 'Verification Approved 🎉',
            'message' => 'Na-approve na ang iyong account verification! Maaari ka nang mag-apply ng mga loan sa sistema.',
            'is_read' => false,
        ]);

        AuditLog::record(auth()->id(), 'Approved Borrower Verification', 'User', $user->id, "Approved verification for {$user->name}");

        return back()->with('status', "Borrower \"{$user->name}\" has been successfully verified.");
    }

    public function reject(User $user): RedirectResponse
    {
        $user->update([
            'verification_status' => 'unverified',
        ]);

        // Magdagdag ng notification sa inbox ng borrower kapag na-reject
        Notification::create([
            'user_id' => $user->id,
            'type' => 'verification_rejected',
            'title' => 'Verification Rejected ⚠️',
            'message' => 'Na-reject ang iyong verification request. Mangyaring suriin at i-update ang iyong mga isinumiteng detalye.',
            'is_read' => false,
        ]);

        AuditLog::record(auth()->id(), 'Rejected Borrower Verification', 'User', $user->id, "Rejected verification for {$user->name}");

        // Pinalitan ng 'error' session para maging kulay pula ang alert sa UI
        return back()->with('error', "Borrower \"{$user->name}\" verification was rejected.");
    }
}