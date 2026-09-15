<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'laboratory_id',
        'branch_id',
         'expense_account_id',
        'invoice_number',
        'supplier_name',
        'supplier_phone',
        'supplier_email',
        'amount',
        'amount_paid',
        'invoice_date',
        'due_date',
        'description',
        'status',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'invoice_date' => 'date',
        'due_date' => 'date',
    ];

    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
    public function expenseAccount(): BelongsTo
{
    return $this->belongsTo(ChartOfAccount::class, 'expense_account_id');
}

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function balance(): float
    {
        return max(
            0,
            (float) $this->amount - (float) $this->amount_paid
        );
    }

    public function isPaid(): bool
    {
        return $this->balance() <= 0;
    }

    public function isOverdue(): bool
    {
        return $this->balance() > 0
            && $this->due_date
            && $this->due_date->isPast();
    }

     public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(SupplierPayment::class);
}
}