<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Delivery;

class DeliveryPolicy
{

    /**
     * Hanya user dengan role 'user' yang bisa create delivery dan email sudah terverifikasi.
     */
    public function create(User $user): bool
    {
        // Check if user has correct role AND email is verified
        $hasCorrectRole = $user->hasRole('admin') || $user->hasRole('user');
        $hasVerifiedEmail = !is_null($user->email_verified_at);

        return $hasCorrectRole && $hasVerifiedEmail;
    }
}
