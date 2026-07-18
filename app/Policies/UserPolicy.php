<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin()
            && $user->laboratory_id === $model->laboratory_id;
    }

    /**
     * Determine whether the user can create users.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin()
            && $user->laboratory_id === $model->laboratory_id;
    }

    /**
     * Determine whether the user can deactivate the model.
     */
    public function delete(User $authUser, User $user): bool
    {
        // Nobody can deactivate themselves
        if ($authUser->id === $user->id) {
            return false;
        }

        // Super Admin can deactivate anyone except themselves
        if ($authUser->isSuperAdmin()) {
            return true;
        }

        // Laboratory Admin can only deactivate users in their own laboratory
        return $authUser->isAdmin()
            && $authUser->laboratory_id === $user->laboratory_id;
    }

    /**
     * We are not using restore.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * We are not permanently deleting users.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}