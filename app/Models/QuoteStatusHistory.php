<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteStatusHistory extends Model
{
    use HasUlids;

    protected $table = 'quotestatushistory';

    protected $primaryKey = 'quoteStatusHistoryId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = null;

    protected $fillable = [
        'quoteRequestId',
        'quoteStatusId',
        'changedBy',
    ];

    protected function casts(): array
    {
        return [
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

    public function status(): BelongsTo
    {
        return $this->belongsTo(
            QuoteStatus::class,
            'quoteStatusId',
            'quoteStatusId'
        );
    }

    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(
            UserAccount::class,
            'changedBy',
            'userId'
        );
    }
}