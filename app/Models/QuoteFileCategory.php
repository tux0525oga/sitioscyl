<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuoteFileCategory extends Model
{
    use HasUlids;

    protected $table = 'quotefilecategory';

    protected $primaryKey = 'quoteFileCategoryId';

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

    public function files(): HasMany
    {
        return $this->hasMany(
            QuoteRequestFile::class,
            'quoteFileCategoryId',
            'quoteFileCategoryId'
        );
    }
}