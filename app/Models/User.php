<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
    use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'laboratory_id',
    'name',
    'email',
    'password',
    'role',
    'is_active',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => UserRole::class,
            'is_active'         => 'boolean',
        ];
    }

    /**
     * Get the laboratory this user belongs to.
     */
    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }

    /**
     * Determine if the user is the super admin.
     */
    public function isSuperAdmin(): bool
{
    return $this->role === UserRole::SUPER_ADMIN;
}

    /**
     * Determine if the user is an administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    /**
     * Determine if the user is a staff member.
     */
    public function isStaff(): bool
    {
        return $this->role === UserRole::STAFF;
    }

    /**
     * Determine if the user account is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }


public function patients(): HasMany
{
    return $this->hasMany(Patient::class, 'created_by');
}

/**
 * Test types created by this user.
 */
public function testTypes(): HasMany
{
    return $this->hasMany(TestType::class, 'created_by');
}

public function requestedTestRequests(): HasMany
{
    return $this->hasMany(TestRequest::class, 'requested_by');
}

public function updatedTestRequests(): HasMany
{
    return $this->hasMany(TestRequest::class, 'updated_by');
}

/**
 * Results uploaded by this user.
 */
public function uploadedResults(): HasMany
{
    return $this->hasMany(Result::class, 'uploaded_by');
}

/**
 * Results verified by this user.
 */
public function verifiedResults(): HasMany
{
    return $this->hasMany(Result::class, 'verified_by');
}
}