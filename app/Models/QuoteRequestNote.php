<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteRequestNote extends Model
{
    use HasUlids;

    protected $table = 'quoterequestnote';

    protected $primaryKey = 'quoteRequestNoteId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'quoteRequestId',
        'userId',
        'noteText',
    ];

    public function quoteRequest(): BelongsTo
    {
        return $this->belongsTo(
            QuoteRequest::class,
            'quoteRequestId',
            'quoteRequestId'
        );
    }

    public function userAccount(): BelongsTo
    {
        return $this->belongsTo(
            UserAccount::class,
            'userId',
            'userId'
        );
    }
}