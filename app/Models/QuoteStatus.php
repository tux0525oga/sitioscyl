<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuoteStatus extends Model
{
    use HasUlids;

    protected $table = 'quotestatus';

    protected $primaryKey = 'quoteStatusId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'name',
        'code',
        'displayOrder',
        'isClosed',
        'isActive',
    ];

    protected function casts(): array
    {
        return [
            'displayOrder' => 'integer',
            'isClosed' => 'boolean',
            'isActive' => 'boolean',
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
        ];
    }

    public function quoteRequests(): HasMany
    {
        return $this->hasMany(
            QuoteRequest::class,
            'quoteStatusId',
            'quoteStatusId'
        );
    }
	public function historyEntries(): HasMany
	{
		return $this->hasMany(
			QuoteStatusHistory::class,
			'quoteStatusId',
			'quoteStatusId'
		);
	}
}