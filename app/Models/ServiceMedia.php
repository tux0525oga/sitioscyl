<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceMedia extends Model
{
    use HasUlids;

    protected $table = 'servicemedia';

    protected $primaryKey = 'serviceMediaId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = null;

    protected $fillable = [
        'serviceId',
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

    public function service(): BelongsTo
    {
        return $this->belongsTo(
            Service::class,
            'serviceId',
            'serviceId'
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