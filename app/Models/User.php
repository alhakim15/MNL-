<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;

/**
 * @method bool hasRole(string|array|\Spatie\Permission\Models\Role $roles, string|null $guard = null)
 */
class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'phone',
        'profile_photo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
    ];

    public function canAccessFilament(): bool
    {
        return $this->role === 'admin' && $this->hasVerifiedEmail();
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return $this->canAccessFilament();
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\CustomVerifyEmail);
    }

    /**
     * Get the deliveries for the user.
     */
    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}
