<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteRequestFile extends Model
{
    use HasUlids;

    protected $table = 'quoterequestfile';

    protected $primaryKey = 'quoteRequestFileId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = null;

    protected $fillable = [
        'quoteRequestId',
        'quoteFileCategoryId',
        'storageDisk',
        'storagePath',
        'fileName',
        'originalFileName',
        'mimeType',
        'fileSize',
        'sha256',
    ];

    protected function casts(): array
    {
        return [
            'fileSize' => 'integer',
            'createdAt' => 'datetime',
        ];
    }

    public function quoteRequest(): BelongsTo
    {
        return $this->belongsTo(
            QuoteRequest::class,
            'quoteRequestId',
            'quoteRequestId'
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            QuoteFileCategory::class,
            'quoteFileCategoryId',
            'quoteFileCategoryId'
        );
    }
}