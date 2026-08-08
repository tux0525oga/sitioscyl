<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    use HasUlids;

    protected $table = 'tag';

    protected $primaryKey = 'tagId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'name',
        'slug',
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

    public function projectLinks(): HasMany
    {
        return $this->hasMany(
            ProjectTag::class,
            'tagId',
            'tagId'
        );
    }
}