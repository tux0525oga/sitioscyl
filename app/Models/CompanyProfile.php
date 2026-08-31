<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyProfile extends Model
{
    use HasUlids;

    protected $table = 'companyprofile';

    protected $primaryKey = 'companyProfileId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';

    public const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'code',
        'companyName',
        'slogan',
        'phoneNumber',
        'whatsAppNumber',
        'contactEmail',
        'addressLine',
        'locationCity',
        'locationState',
        'postalCode',
        'businessHours',
        'logoMediaId',
        'monogramMediaId',
        'homeHeroMediaId',
    ];

    public function logo(): BelongsTo
    {
        return $this->belongsTo(
            MediaAsset::class,
            'logoMediaId',
            'mediaId'
        );
    }

    public function monogram(): BelongsTo
    {
        return $this->belongsTo(
            MediaAsset::class,
            'monogramMediaId',
            'mediaId'
        );
    }

    public function homeHeroMedia(): BelongsTo
    {
        return $this->belongsTo(
            MediaAsset::class,
            'homeHeroMediaId',
            'mediaId'
        );
    }
}
