<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaboratoryModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'laboratory_id',
        'module',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }
}