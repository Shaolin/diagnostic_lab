<?php

namespace App\Policies;

use App\Models\Laboratory;
use App\Models\User;

class LaboratoryPolicy
{
    /**
     * Determine whether the user can view any laboratories.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Determine whether the user can view a laboratory.
     */
    public function view(User $user, Laboratory $laboratory): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin()
            && $user->laboratory_id === $laboratory->id;
    }

    /**
     * Determine whether the user can create laboratories.
     *
     * Registration creates laboratories, not authenticated users.
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update a laboratory.
     */
    public function update(User $user, Laboratory $laboratory): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin()
            && $user->laboratory_id === $laboratory->id;
    }

    /**
     * Determine whether the user can delete a laboratory.
     */
    public function delete(User $user, Laboratory $laboratory): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Restore is not used.
     */
    public function restore(User $user, Laboratory $laboratory): bool
    {
        return false;
    }

    /**
     * Permanent deletion is not used.
     */
    public function forceDelete(User $user, Laboratory $laboratory): bool
    {
        return false;
    }
}