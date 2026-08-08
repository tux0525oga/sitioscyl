<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectComparison extends Model
{
    use HasUlids;

    protected $table = 'projectcomparison';

    protected $primaryKey = 'projectComparisonId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'projectId',
        'beforeMediaId',
        'afterMediaId',
        'title',
        'description',
        'displayOrder',
        'isPublished',
    ];

    protected function casts(): array
    {
        return [
            'displayOrder' => 'integer',
            'isPublished' => 'boolean',
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

    public function beforeImage(): BelongsTo
    {
        return $this->belongsTo(
            MediaAsset::class,
            'beforeMediaId',
            'mediaId'
        );
    }

    public function afterImage(): BelongsTo
    {
        return $this->belongsTo(
            MediaAsset::class,
            'afterMediaId',
            'mediaId'
        );
    }
}