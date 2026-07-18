<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;




class TestRequestItem extends Model
{


public const STATUS_PENDING = 'Pending';
public const STATUS_IN_PROGRESS = 'In Progress';
public const STATUS_COMPLETED = 'Completed';

public const SAMPLE_PENDING = 'Pending';
public const SAMPLE_COLLECTED = 'Collected';

public const RESULT_NOT_READY = 'Not Ready';
public const RESULT_READY = 'Ready';
public const RESULT_SENT = 'Sent';


    protected $fillable = [
        'test_request_id',
        'test_type_id',
        'test_name',
        'price',
        'status',
        'sample_status',
        'result_status',
        
        'completed_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function testRequest(): BelongsTo
    {
        return $this->belongsTo(TestRequest::class);
    }

    public function testType(): BelongsTo
    {
        return $this->belongsTo(TestType::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'Pending');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'Completed');
    }

    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', 'In Progress');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

   
public function isCompleted(): bool
{
    return $this->status === self::STATUS_COMPLETED;
}
    public function isSampleCollected(): bool
    {
        return $this->sample_status === 'Collected';
    }

    public function isResultReady(): bool
    {
        return $this->result_status === 'Ready';
    }

    public function isResultSent(): bool
    {
        return $this->result_status === 'Sent';
    }
    /**
 * The uploaded result for this test item.
 */
public function result(): HasOne
{
    return $this->hasOne(Result::class);
}




}