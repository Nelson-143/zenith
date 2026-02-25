<?php

namespace app\Policies;

use app\Models\Subscription;
use app\Models\User;
use Illuminate\Auth\Access\Response;

class SubscriptionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function manage(User $user)
    {
        return $user->role === 'superadmin'; // Replace with your role-check logic
    }
    
}
