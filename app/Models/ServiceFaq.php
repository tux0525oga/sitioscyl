<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceFaq extends Model
{
    use HasUlids;

    protected $table = 'servicefaq';

    protected $primaryKey = 'serviceFaqId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = null;

    protected $fillable = [
        'serviceId',
        'faqId',
        'displayOrder',
    ];

    protected function casts(): array
    {
        return [
            'displayOrder' => 'integer',
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

    public function faq(): BelongsTo
    {
        return $this->belongsTo(
            Faq::class,
            'faqId',
            'faqId'
        );
    }
}
