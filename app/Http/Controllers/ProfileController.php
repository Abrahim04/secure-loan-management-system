<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Notification;
use App\Models\SecurityEvent;
use App\Models\User;
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

    /**
     * I-update ang Personal Details para sa Admin at Borrower accounts.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'phone'             => ['required', 'string', 'max:20'],
            'dob'               => ['required', 'date'],
            'gender'            => ['nullable', 'string', 'max:50'],
            'nationality'       => ['nullable', 'string', 'max:100'],
            'address'           => ['required', 'string', 'max:500'],
            'id_type'           => ['nullable', 'string', 'max:100'],
            'id_number'         => ['nullable', 'string', 'max:100'],
            'id_picture'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'employment_status' => ['nullable', 'string', 'max:50'],
            'occupation'        => ['nullable', 'string', 'max:255'],
            'job_position'      => ['nullable', 'string', 'max:255'],
            'monthly_income'    => ['nullable', 'string', 'max:100'],
            'income_source'     => ['nullable', 'string', 'max:255'],
        ]);

        // Handle ID Picture / Document upload kung may inupload
        if ($request->hasFile('id_picture')) {
            if ($user->id_image_path && Storage::disk('public')->exists($user->id_image_path)) {
                Storage::disk('public')->delete($user->id_image_path);
            }
            $validated['id_image_path'] = $request->file('id_picture')->store('verification-ids', 'public');
        }

        unset($validated['id_picture']);

        $user->update($validated);

        AuditLog::record($user->id, 'Updated Profile Information', 'User', $user->id);

        return back()->with('status', 'Personal details updated successfully.');
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
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            SecurityEvent::record($user->id, 'failed_password_change', 'Incorrect current password entered while changing password');

            throw ValidationException::withMessages([
                'current_password' => 'Your current password is incorrect.',
            ]);
        }

        $user->update(['password' => $validated['password']]);

        AuditLog::record($user->id, 'Changed Password', 'User', $user->id);
        SecurityEvent::record($user->id, 'password_changed', 'Password changed from account settings');

        return back()->with('status', 'Password changed successfully.');
    }

    /**
     * Ipakita ang hiwalay na pahina ng Borrower Verification Form.
     */
    public function showVerificationForm(): View|RedirectResponse
    {
        $user = auth()->user();

        // Kung verified o pending na, huwag nang payagang pumunta sa form page
        if ($user->verification_status !== 'unverified') {
            return redirect()->route('profile.show')
                ->with('status', 'Your account is currently under review or already verified.');
        }

        return view('profile.verify-form', ['user' => $user]);
    }

    /**
     * Handle borrower verification submission (Core Info, ID, Employment & Income).
     */
    public function submitVerification(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'dob' => ['required', 'date'],
            'gender' => ['required', 'string', 'max:50'],
            'nationality' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
            'id_type' => ['required', 'string', 'max:100'],
            'id_number' => ['required', 'string', 'max:100'],
            'id_image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:4096'],
            'employment_status' => ['required', 'string', 'max:50'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'job_position' => ['nullable', 'string', 'max:255'],
            'monthly_income' => ['required', 'string', 'max:100'],
            'income_source' => ['nullable', 'string', 'max:255'],
        ]);

        // Handle Valid ID image upload
        if ($request->hasFile('id_image')) {
            if ($user->id_image_path && Storage::disk('public')->exists($user->id_image_path)) {
                Storage::disk('public')->delete($user->id_image_path);
            }
            $validated['id_image_path'] = $request->file('id_image')->store('verification-ids', 'public');
        }

        unset($validated['id_image']);
        
        // Itakda ang verification status sa pending habang naghihintay ng admin approval
        $validated['verification_status'] = 'pending';

        $user->update($validated);

        AuditLog::record($user->id, 'Submitted Verification Details', 'User', $user->id);

        // Magpadala ng notification sa lahat ng Admin na may nag-sumite ng KYC Verification
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'verification',
                'title' => 'New KYC Verification Submitted',
                'message' => $user->name . ' has submitted their verification details for review.',
                'is_read' => false,
            ]);
        }

        return redirect()->route('profile.show')
            ->with('status', 'Verification details submitted successfully. Awaiting admin approval.');
    }
}