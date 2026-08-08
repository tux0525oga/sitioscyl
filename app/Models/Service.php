<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasUlids;
    use SoftDeletes;

    protected $table = 'service';

    protected $primaryKey = 'serviceId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';

    public const UPDATED_AT = 'updatedAt';

    public const DELETED_AT = 'deletedAt';

    protected $fillable = [
        'name',
        'slug',
        'shortDescription',
        'description',
        'heroTitle',
        'heroSubtitle',
        'featuredImageId',
        'displayOrder',
        'isFeatured',
        'isPublished',
        'publishedAt',
    ];

    protected function casts(): array
    {
        return [
            'displayOrder' => 'integer',
            'isFeatured' => 'boolean',
            'isPublished' => 'boolean',
            'publishedAt' => 'datetime',
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
            'deletedAt' => 'datetime',
        ];
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(
            MediaAsset::class,
            'featuredImageId',
            'mediaId'
        );
    }

    public function solutions(): HasMany
    {
        return $this->hasMany(
            ServiceSolution::class,
            'serviceId',
            'serviceId'
        )->orderBy('displayOrder');
    }

    public function benefits(): HasMany
    {
        return $this->hasMany(
            ServiceBenefit::class,
            'serviceId',
            'serviceId'
        )->orderBy('displayOrder');
    }
}
