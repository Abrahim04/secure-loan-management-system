<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\SecurityEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        return view('profile.show', ['user' => auth()->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update($validated);

        AuditLog::record($user->id, 'Updated Profile', 'User', $user->id);

        return back()->with('status', 'Profile updated successfully.');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Remove the old photo, if any, before storing the new one.
        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar_path' => $path]);

        AuditLog::record($user->id, 'Updated Profile Photo', 'User', $user->id);

        return back()->with('status', 'Profile photo updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            SecurityEvent::record($user->id, 'failed_password_change', 'Incorrect current password entered while changing password');

            throw ValidationException::withMessages([
                'current_password' => 'Your current password is incorrect.',
            ]);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        AuditLog::record($user->id, 'Changed Password', 'User', $user->id);
        SecurityEvent::record($user->id, 'password_changed', 'Password changed from account settings');

        return back()->with('status', 'Password changed successfully.');
    }
}
