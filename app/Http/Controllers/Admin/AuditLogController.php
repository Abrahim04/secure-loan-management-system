<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $date = $request->input('date');

        $logs = AuditLog::with('user')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($subQ) use ($search) {
                    $subQ->where('action', 'like', '%' . $search . '%')
                         ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', '%' . $search . '%'));
                });
            })
            ->when($date, fn ($q) => $q->whereDate('created_at', $date))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.audit-logs.index', compact('logs'));
    }
}