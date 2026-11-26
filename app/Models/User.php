<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'is_active',
        'avatar_path',
        'email_verified_at',
        // Mga bagong ID at Verification fields:
        'verification_status',
        'dob',
        'gender',
        'nationality',
        'id_type',
        'id_number',
        'id_image_path',
        'employment_status',
        'occupation',
        'job_position',
        'monthly_income',
        'income_source',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'dob' => 'date',
        ];
    }

    // --- Relationships ---

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function loansReviewed(): HasMany
    {
        return $this->hasMany(Loan::class, 'reviewed_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function paymentsVerified(): HasMany
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }

    public function appNotifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function securityEvents(): HasMany
    {
        return $this->hasMany(SecurityEvent::class);
    }

    public function mfaCodes(): HasMany
    {
        return $this->hasMany(Mfa::class);
    }

    // --- Helpers ---

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Tinitingnan kung ang Admin ay kulang pa sa kinakailangang personal details.
     * Mag-o-trigger ito ng Red Alert Notification Badge sa Sidebar at Profile UI.
     */
    public function isAdminIncomplete(): bool
    {
        if ($this->isAdmin()) {
            return empty($this->phone) || empty($this->address) || empty($this->dob);
        }
        return false;
    }

    /**
     * Returns the URL to the user's uploaded avatar, or a generated
     * initials-based placeholder if none has been uploaded.
     * Uses a streaming route instead of the public storage symlink,
     * since `storage:link` often fails on Windows without admin rights.
     */
    public function avatarUrl(): string
    {
        if ($this->avatar_path && Storage::disk('public')->exists($this->avatar_path)) {
            return route('avatar.show', $this);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=1a1d2e&color=fff&size=128';
    }
}