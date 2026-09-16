<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryStockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'laboratory_id',
        'branch_id',
        'inventory_item_id',
        'inventory_stock_id',
        'type',
        'quantity',
        'unit_cost',
        'reference',
        'description',
        'movement_date',
        'recorded_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'movement_date' => 'date',
    ];

    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function inventoryStock(): BelongsTo
    {
        return $this->belongsTo(InventoryStock::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}