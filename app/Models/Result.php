<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_request_item_id',
        'uploaded_by',
        'verified_by',
        'pdf_path',
        'remarks',
        'uploaded_at',
        'verified_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * The Test Request Item this result belongs to.
     */
    public function testRequestItem(): BelongsTo
    {
        return $this->belongsTo(TestRequestItem::class);
    }

    /**
     * User who uploaded the result.
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * User who verified the result.
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}