<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceSolution extends Model
{
    use HasUlids;

    protected $table = 'servicesolution';

    protected $primaryKey = 'serviceSolutionId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';

    public const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'serviceId',
        'name',
        'slug',
        'shortDescription',
        'description',
        'featuredImageId',
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

    public function service(): BelongsTo
    {
        return $this->belongsTo(
            Service::class,
            'serviceId',
            'serviceId'
        );
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(
            MediaAsset::class,
            'featuredImageId',
            'mediaId'
        );
    }
}
