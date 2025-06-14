<?php

namespace App\Policies;

use App\Models\FinancialGoal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class FinancialGoalPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): bool|null
    {
        Log::info('Policy before method called', [
            'user_id' => $user->id,
            'ability' => $ability
        ]);
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, FinancialGoal $financialGoal): bool
    {

        if (!$user) {
            return false;
        }
        return $user->id === $financialGoal->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, FinancialGoal $financialGoal): bool
    {
        if (!$user) {
            return false;
        }
        return $user->id === $financialGoal->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, FinancialGoal $financialGoal): bool
    {
        if (!$user) {
            return false;
        }
        return $user->id === $financialGoal->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FinancialGoal $financialGoal): bool
    {
        return $user->id === $financialGoal->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FinancialGoal $financialGoal): bool
    {
        return $user->id === $financialGoal->user_id;
    }
} 