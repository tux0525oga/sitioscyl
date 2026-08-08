<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectMedia extends Model
{
    use HasUlids;

    protected $table = 'projectmedia';

    protected $primaryKey = 'projectMediaId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = null;

    protected $fillable = [
        'projectId',
        'mediaId',
        'mediaCategoryId',
        'displayOrder',
        'isFeatured',
    ];

    protected function casts(): array
    {
        return [
            'displayOrder' => 'integer',
            'isFeatured' => 'boolean',
            'createdAt' => 'datetime',
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

    public function mediaAsset(): BelongsTo
    {
        return $this->belongsTo(
            MediaAsset::class,
            'mediaId',
            'mediaId'
        );
    }

    public function mediaCategory(): BelongsTo
    {
        return $this->belongsTo(
            MediaCategory::class,
            'mediaCategoryId',
            'mediaCategoryId'
        );
    }
}