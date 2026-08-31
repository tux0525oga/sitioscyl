<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasUlids;
    use SoftDeletes;

    protected $table = 'contact';

    protected $primaryKey = 'contactId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    public const DELETED_AT = 'deletedAt';

    protected $fillable = [
        'firstName',
        'lastName',
        'phoneNumber',
        'whatsAppNumber',
        'email',
        'preferredContactMethodId',
    ];

    protected function casts(): array
    {
        return [
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
            'deletedAt' => 'datetime',
        ];
    }

    public function preferredContactMethod(): BelongsTo
    {
        return $this->belongsTo(
            ContactMethod::class,
            'preferredContactMethodId',
            'contactMethodId'
        );
    }

    public function quoteRequests(): HasMany
    {
        return $this->hasMany(
            QuoteRequest::class,
            'contactId',
            'contactId'
        );
    }
}