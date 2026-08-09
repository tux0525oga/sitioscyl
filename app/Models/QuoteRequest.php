<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuoteRequest extends Model
{
    use HasUlids;
    use SoftDeletes;

    protected $table = 'quoterequest';

    protected $primaryKey = 'quoteRequestId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    public const DELETED_AT = 'deletedAt';

    protected $fillable = [
        'folio',
        'contactId',
        'description',
        'locationCity',
        'locationState',
        'locationNeighborhood',
        'preferredTimeframeId',
        'referenceProjectId',
        'sourcePage',
        'sourceUrl',
        'quoteStatusId',
    ];

    protected function casts(): array
    {
        return [
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
            'deletedAt' => 'datetime',
        ];
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(
            Contact::class,
            'contactId',
            'contactId'
        );
    }

    public function preferredTimeframe(): BelongsTo
    {
        return $this->belongsTo(
            PreferredTimeframe::class,
            'preferredTimeframeId',
            'preferredTimeframeId'
        );
    }

    public function referenceProject(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'referenceProjectId',
            'projectId'
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

    public function serviceLinks(): HasMany
    {
        return $this->hasMany(
            QuoteRequestService::class,
            'quoteRequestId',
            'quoteRequestId'
        );
    }
	public function files(): HasMany
	{
		return $this->hasMany(
			QuoteRequestFile::class,
			'quoteRequestId',
			'quoteRequestId'
		);
	}
	public function notes(): HasMany
	{
		return $this->hasMany(
			QuoteRequestNote::class,
			'quoteRequestId',
			'quoteRequestId'
		)->orderBy('createdAt');
	}

	public function statusHistory(): HasMany
	{
		return $this->hasMany(
			QuoteStatusHistory::class,
			'quoteRequestId',
			'quoteRequestId'
		)->orderBy('createdAt');
	}
}