<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityEvent;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SecurityEventController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $eventType = $request->input('event_type');
        $date = $request->input('date');

        $events = SecurityEvent::with('user')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($subQ) use ($search) {
                    $subQ->where('ip_address', 'like', '%' . $search . '%')
                         ->orWhere('description', 'like', '%' . $search . '%')
                         ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', '%' . $search . '%'));
                });
            })
            ->when($eventType && $eventType !== 'all', fn ($q) => $q->where('event_type', $eventType))
            ->when($date, fn ($q) => $q->whereDate('created_at', $date))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $eventTypes = SecurityEvent::select('event_type')->distinct()->pluck('event_type');

        return view('admin.security-events.index', compact('events', 'eventTypes'));
    }
}