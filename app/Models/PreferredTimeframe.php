<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreferredTimeframe extends Model
{
    use HasUlids;

    protected $table = 'preferredtimeframe';

    protected $primaryKey = 'preferredTimeframeId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'name',
        'code',
        'displayOrder',
        'isActive',
    ];

    protected function casts(): array
    {
        return [
            'displayOrder' => 'integer',
            'isActive' => 'boolean',
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
        ];
    }

    public function quoteRequests(): HasMany
    {
        return $this->hasMany(
            QuoteRequest::class,
            'preferredTimeframeId',
            'preferredTimeframeId'
        );
    }
}