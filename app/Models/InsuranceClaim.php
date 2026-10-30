<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InsuranceClaim extends Model
{
    protected $fillable = [
        'laboratory_id',
        'test_request_id',
        'insurance_company',
        'membership_number',
        'covered_amount',
        'patient_contribution',
        'status',
        'remarks',
    ];

    protected $casts = [
        'covered_amount' => 'decimal:2',
        'patient_contribution' => 'decimal:2',
    ];

    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function testRequest(): BelongsTo
    {
        return $this->belongsTo(TestRequest::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(InsuranceClaimPayment::class);
    }

    public function totalSettled(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function outstandingAmount(): float
    {
        return max(
            0,
            (float) $this->covered_amount - $this->totalSettled()
        );
    }
}
