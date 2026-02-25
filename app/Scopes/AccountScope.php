<?php
namespace app\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class AccountScope implements Scope
{// app/Scopes/AccountScope.php
public function apply(Builder $builder, Model $model)
{
    // Only apply scope if user is logged in
    if (auth()->check()) {
        // For all non-superadmin users
        if (!optional(auth()->user())->is_superadmin) {
            $builder->where('account_id', auth()->user()->account_id);
        }
    }
}
}



// for the spaite
/* public function apply(Builder $builder, Model $model)
{
    // Get the authenticated user
   // $user = Auth::user();

    // Apply the account filter if the user is logged in and not a Super Admin
    if ($user && !$user->hasRole('Super Admin')) {
        $builder->where('account_id', $user->account_id);
    }

    // for the custome
         public function apply(Builder $builder, Model $model)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Apply the account filter if the user is logged in and not a Super Admin
        if ($user && $user->role !== 'Super Admin') { // Replace with your custom role check
            $builder->where('account_id', $user->account_id);
        }
    }
}
    */