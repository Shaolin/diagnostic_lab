<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalResultFile extends Model
{
    protected $fillable = [
        'test_request_item_id',
        'uploaded_by',
        'file_path',
        'original_name',
        'file_type',
        'mime_type',
        'description',
    ];

    /**
     * The test request item this file belongs to.
     */
    public function testRequestItem(): BelongsTo
    {
        return $this->belongsTo(TestRequestItem::class);
    }

    /**
     * The staff member who uploaded the file.
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
