<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestRequest extends Model
{


public const STATUS_PENDING = 'Pending';
public const STATUS_IN_PROGRESS = 'In Progress';
public const STATUS_PARTIALLY_COMPLETED = 'Partially Completed';
public const STATUS_COMPLETED = 'Completed';

public const PAYMENT_UNPAID = 'Unpaid';
public const PAYMENT_PARTIALLY_PAID = 'Partially Paid';
public const PAYMENT_PAID = 'Paid';

    protected $fillable = [
        'laboratory_id',
        'patient_id',
        'tracking_code',
        'total_amount',
        'remarks',
        'overall_status',
        'requested_by',
        'updated_by',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TestRequestItem::class);
    }
    public function payments(): HasMany
{
    return $this->hasMany(Payment::class);
}

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeForLaboratory(Builder $query, int $laboratoryId): Builder
    {
        return $query->where('laboratory_id', $laboratoryId);
    }

    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('overall_status', $status);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function getTestsCountAttribute(): int
    {
        return $this->items()->count();
    }

    public function isCompleted(): bool
    {
        return $this->overall_status === 'Completed';
    }

    public function isPending(): bool
    {
        return $this->overall_status === 'Pending';
    }

    public function isInProgress(): bool
    {
        return $this->overall_status === 'In Progress';
    }

    /**
 * Get the total amount paid for this test request.
 */
public function totalPaid(): float
{
    return (float) $this->payments()->sum('amount');
}

/**
 * Get the outstanding balance for this test request.
 */
public function balance(): float
{
    return max(0, (float) $this->total_amount - $this->totalPaid());
}

/**
 * Get the current payment status.
 */
public function paymentStatus(): string
{
    $total = (float) $this->total_amount;
    $paid = $this->totalPaid();

    if ($paid <= 0) {
        return self::PAYMENT_UNPAID;
    }

    if ($paid >= $total) {
        return self::PAYMENT_PAID;
    }

    return self::PAYMENT_PARTIALLY_PAID;
}

/**
 * Determine whether the test request has been fully paid.
 */
public function isPaid(): bool
{
    return $this->paymentStatus() === self::PAYMENT_PAID;
}

/**
 * Determine whether the test request is partially paid.
 */
public function isPartiallyPaid(): bool
{
    return $this->paymentStatus() === self::PAYMENT_PARTIALLY_PAID;
}

/**
 * Determine whether the test request is unpaid.
 */
public function isUnpaid(): bool
{
    return $this->paymentStatus() === self::PAYMENT_UNPAID;
}

}