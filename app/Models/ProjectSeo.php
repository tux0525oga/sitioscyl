<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectSeo extends Model
{
    use HasUlids;

    protected $table = 'projectseo';

    protected $primaryKey = 'projectSeoId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'projectId',
        'metaTitle',
        'metaDescription',
        'canonicalUrl',
        'socialTitle',
        'socialDescription',
        'socialImageId',
        'robotsIndex',
        'robotsFollow',
    ];

    protected function casts(): array
    {
        return [
            'robotsIndex' => 'boolean',
            'robotsFollow' => 'boolean',
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'projectId',
            'projectId'
        );
    }

    public function socialImage(): BelongsTo
    {
        return $this->belongsTo(
            MediaAsset::class,
            'socialImageId',
            'mediaId'
        );
    }
}