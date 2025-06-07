<?php

namespace App\Policies;

use App\Models\CreditCard;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CreditCardPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, CreditCard $creditCard)
    {
        return $user->id === $creditCard->user_id;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, CreditCard $creditCard)
    {
        return $user->id === $creditCard->user_id;
    }

    public function delete(User $user, CreditCard $creditCard)
    {
        return $user->id === $creditCard->user_id;
    }
} 