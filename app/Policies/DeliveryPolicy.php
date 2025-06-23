<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Delivery;

class DeliveryPolicy
{

    /**
     * Hanya user dengan role 'user' yang bisa create delivery.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('user');
    }
}
