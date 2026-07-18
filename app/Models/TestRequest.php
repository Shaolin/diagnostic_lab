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

}