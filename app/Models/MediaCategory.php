<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaCategory extends Model
{
    use HasUlids;

    protected $table = 'mediacategory';

    protected $primaryKey = 'mediaCategoryId';

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

    public function serviceMediaLinks(): HasMany
    {
        return $this->hasMany(
            ServiceMedia::class,
            'mediaCategoryId',
            'mediaCategoryId'
        );
    }
}
