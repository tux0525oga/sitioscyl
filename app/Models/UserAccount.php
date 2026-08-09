<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserAccount extends Authenticatable
{
    use HasUlids;
    use Notifiable;
    use SoftDeletes;

    protected $table = 'useraccount';

    protected $primaryKey = 'userId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';

    public const UPDATED_AT = 'updatedAt';

    public const DELETED_AT = 'deletedAt';

    protected $fillable = [
        'userRoleId',
        'firstName',
        'lastName',
        'email',
        'passwordHash',
        'isActive',
        'lastLoginAt',
    ];

    protected $hidden = [
        'passwordHash',
    ];

    protected function casts(): array
    {
        return [
            'isActive' => 'boolean',
            'lastLoginAt' => 'datetime',
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
            'deletedAt' => 'datetime',
        ];
    }

    public function userRole(): BelongsTo
    {
        return $this->belongsTo(
            UserRole::class,
            'userRoleId',
            'userRoleId'
        );
    }

    public function getAuthPasswordName(): string
    {
        return 'passwordHash';
    }
    public function uploadedMediaAssets(): HasMany
    {
        return $this->hasMany(
            MediaAsset::class,
            'uploadedBy',
            'userId'
         );
    }
	public function quoteRequestNotes(): HasMany
	{
		return $this->hasMany(
			QuoteRequestNote::class,
			'userId',
			'userId'
		);
	}

	public function quoteStatusChanges(): HasMany
	{
		return $this->hasMany(
			QuoteStatusHistory::class,
			'changedBy',
			'userId'
		);
	}
	public function role(): BelongsTo
	{
		return $this->belongsTo(
			UserRole::class,
			'userRoleId',
			'userRoleId'
		);
	}

	public function hasAdminAccess(): bool
	{
		if (!$this->isActive) {
			return false;
    }

    return $this->role()
        ->where('isActive', true)
        ->whereIn('code', [
            'Administrator',
            'Editor',
        ])
        ->exists();
	}
}