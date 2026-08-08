<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceSeo extends Model
{
    use HasUlids;

    protected $table = 'serviceseo';

    protected $primaryKey = 'serviceSeoId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'serviceId',
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

    public function service(): BelongsTo
    {
        return $this->belongsTo(
            Service::class,
            'serviceId',
            'serviceId'
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