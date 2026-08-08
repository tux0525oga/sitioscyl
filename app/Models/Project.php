<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasUlids;
    use SoftDeletes;

    protected $table = 'project';

    protected $primaryKey = 'projectId';

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
        'challengeDescription',
        'solutionDescription',
        'locationCity',
        'locationState',
        'projectYear',
        'featuredImageId',
        'displayOrder',
        'isFeatured',
        'isPublished',
        'publishedAt',
    ];

    protected function casts(): array
    {
        return [
            'projectYear' => 'integer',
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

    public function serviceLinks(): HasMany
    {
        return $this->hasMany(
            ProjectService::class,
            'projectId',
            'projectId'
        )->orderBy('displayOrder');
    }

    public function mediaLinks(): HasMany
    {
        return $this->hasMany(
            ProjectMedia::class,
            'projectId',
            'projectId'
        )->orderBy('displayOrder');
    }

    public function tagLinks(): HasMany
    {
        return $this->hasMany(
            ProjectTag::class,
            'projectId',
            'projectId'
        );
    }

    public function comparisons(): HasMany
    {
        return $this->hasMany(
            ProjectComparison::class,
            'projectId',
            'projectId'
        )->orderBy('displayOrder');
    }
}