<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = auth()->user()->appNotifications()->latest()->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        $notification->update(['is_read' => true]);

        return back();
    }

    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()->appNotifications()->where('is_read', false)->update(['is_read' => true]);

        return back()->with('status', 'All notifications marked as read.');
    }
}
