<?php

namespace app\Policies;

use app\Models\Product;
use app\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */// app/Policies/ProductPolicy.php

public function view(User $user, Product $product)
{
    return $user->account_id === $product->account_id;
}

public function update(User $user, Product $product)
{
    return $user->account_id === $product->account_id;
}

public function delete(User $user, Product $product)
{
    return $user->account_id === $product->account_id;
}
}
