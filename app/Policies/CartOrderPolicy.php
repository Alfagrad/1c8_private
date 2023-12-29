<?php

namespace App\Policies;

use App\Models\CartOrder;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CartOrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CartOrder $cartOrder): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CartOrder $cartOrder): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CartOrder $cartOrder): bool
    {
        return $user->profile->id == $cartOrder->profile_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CartOrder $cartOrder): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CartOrder $cartOrder): bool
    {
        //
    }
}
