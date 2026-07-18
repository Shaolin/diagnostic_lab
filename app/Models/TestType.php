<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestType extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'laboratory_id',
        'code',
        'name',
        'category',
        'description',
        'default_price',
        'estimated_turnaround_hours',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'default_price' => 'decimal:2',
            'estimated_turnaround_hours' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Laboratory that owns this test type.
     */
    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }

    /**
     * User who created this record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who last updated this record.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Only active test types.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Only inactive test types.
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    /**
     * Restrict records to a laboratory.
     */
    public function scopeForLaboratory(Builder $query, int $laboratoryId): Builder
    {
        return $query->where('laboratory_id', $laboratoryId);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Human-readable turnaround time.
     */
    public function getTurnaroundLabelAttribute(): string
    {
        if (!$this->estimated_turnaround_hours) {
            return 'N/A';
        }

        return $this->estimated_turnaround_hours . ' Hour'
            . ($this->estimated_turnaround_hours > 1 ? 's' : '');
    }

    /**
     * Human-readable status.
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    public function testRequestItems(): HasMany
{
    return $this->hasMany(TestRequestItem::class);
}

    /*
    |--------------------------------------------------------------------------
    | Future Relationships
    |--------------------------------------------------------------------------
    |
    | These will be added later:
    |
    | - departments()
    | - laboratorySection()
    | - sampleTypes()
    | - resultTemplate()
    | 
    |
    */
}