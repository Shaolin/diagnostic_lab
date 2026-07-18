<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

class PatientPolicy
{
    /**
     * Determine whether the user can view any patients.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the patient.
     */
    public function view(User $user, Patient $patient): bool
    {
        return $user->laboratory_id === $patient->laboratory_id;
    }

    /**
     * Determine whether the user can create patients.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the patient.
     */
    public function update(User $user, Patient $patient): bool
    {
        return $user->laboratory_id === $patient->laboratory_id;
    }

    /**
     * Determine whether the user can deactivate/reactivate the patient.
     */
    public function deactivate(User $user, Patient $patient): bool
    {
        return $user->laboratory_id === $patient->laboratory_id;
    }
}