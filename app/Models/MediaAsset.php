<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaAsset extends Model
{
    use HasUlids;
    use SoftDeletes;

    protected $table = 'mediaasset';

    protected $primaryKey = 'mediaId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';

    public const UPDATED_AT = 'updatedAt';

    public const DELETED_AT = 'deletedAt';

    protected $fillable = [
        'uploadedBy',
        'storageDisk',
        'storagePath',
        'fileName',
        'originalFileName',
        'mimeType',
        'fileExtension',
        'fileSize',
        'width',
        'height',
        'sha256',
        'title',
        'altText',
        'description',
        'isPublic',
        'isPublished',
    ];

    protected function casts(): array
    {
        return [
            'fileSize' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
            'isPublic' => 'boolean',
            'isPublished' => 'boolean',
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
            'deletedAt' => 'datetime',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(
            UserAccount::class,
            'uploadedBy',
            'userId'
        );
    }

    public function serviceLinks(): HasMany
    {
        return $this->hasMany(
            ServiceMedia::class,
            'mediaId',
            'mediaId'
        );
    }

    public function projectLinks(): HasMany
    {
        return $this->hasMany(
            ProjectMedia::class,
            'mediaId',
            'mediaId'
        );
    }
}
