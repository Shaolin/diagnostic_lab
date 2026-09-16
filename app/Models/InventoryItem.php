<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'laboratory_id',
        'name',
        'category',
        'unit',
        'description',
        'minimum_stock',
        'is_active',
    ];

    protected $casts = [
        'minimum_stock' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }
}