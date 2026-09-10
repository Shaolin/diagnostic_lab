<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laboratory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'subdomain',
        'logo',
        'phone',
        'email',
        'address',
        'country',
        'currency_code',
        'currency_symbol',
        'timezone',
        'is_active',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
 * Get the full URL for the laboratory logo.
 */
public function getLogoUrlAttribute(): ?string
{
    return $this->logo
        ? asset('storage/' . $this->logo)
        : null;
}
public function users()
{
    return $this->hasMany(User::class);
}



public function patients(): HasMany
{
    return $this->hasMany(Patient::class);
}

/**
 * Laboratory test types.
 */
public function testTypes(): HasMany
{
    return $this->hasMany(TestType::class);
}

public function testRequests(): HasMany
{
    return $this->hasMany(TestRequest::class);
}

    /*
    |--------------------------------------------------------------------------
    | Future Relationships
    |--------------------------------------------------------------------------
    |
    | This model represents a tenant in the application. In future modules,
    | every resource should belong to a laboratory via a `laboratory_id`
    | foreign key.
    |
    | Planned relationships include:
    |
    | 
    |
    | public function payments()
    | {
    |     return $this->hasMany(Payment::class);
    | }
    |
    | These relationships should be implemented when their corresponding
    | models and database tables are created.
    |
    */
}