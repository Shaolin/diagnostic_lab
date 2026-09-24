<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPermission extends Model
{
   public const MODULES = [
    // Laboratory Operations
    'patients',
    'test_types',
    'test_requests',
    'results',
    'payments',

    // Laboratory Management
    'laboratories',
    'users',
    'branches',
];

    protected $fillable = [
        'user_id',
        'module',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * The user this permission belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}