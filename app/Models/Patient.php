<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'laboratory_id',
        'patient_number',
        'first_name',
        'last_name',
        'other_names',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'address',
        'blood_group',
        'genotype',
        'emergency_contact_name',
        'emergency_contact_phone',
        'notes',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Placeholder for future implementation.
     */
    // public function testRequests(): HasMany
    // {
    //     return $this->hasMany(TestRequest::class);
    // }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute(): string
    {
        return collect([
             $this->last_name,
        $this->first_name,
        $this->other_names,
        ])
            ->filter()
            ->implode(' ');
    }

    public function getAgeAttribute(): string
    {
        if (!$this->date_of_birth) {
            return 'N/A';
        }

        return (string) Carbon::parse($this->date_of_birth)->age;
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function ($query) use ($search) {
           $query
    ->where('patient_number', 'like', "%{$search}%")
    ->orWhere('first_name', 'like', "%{$search}%")
    ->orWhere('last_name', 'like', "%{$search}%")
    ->orWhere('other_names', 'like', "%{$search}%")
    ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }
    public function testRequests(): HasMany
{
    return $this->hasMany(TestRequest::class);
}
}