<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserRole extends Model
{
    use HasUlids;

    protected $table = 'userrole';

    protected $primaryKey = 'userRoleId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';

    public const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'name',
        'code',
        'description',
        'isActive',
    ];

    protected function casts(): array
    {
        return [
            'isActive' => 'boolean',
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
        ];
    }

    public function userAccounts(): HasMany
    {
        return $this->hasMany(
            UserAccount::class,
            'userRoleId',
            'userRoleId'
        );
    }
}