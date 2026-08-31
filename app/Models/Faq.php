<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use HasUlids;
    use SoftDeletes;

    protected $table = 'faq';

    protected $primaryKey = 'faqId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    public const DELETED_AT = 'deletedAt';

    protected $fillable = [
        'question',
        'answer',
        'isPublished',
    ];

    protected function casts(): array
    {
        return [
            'isPublished' => 'boolean',
            'createdAt' => 'datetime',
            'updatedAt' => 'datetime',
            'deletedAt' => 'datetime',
        ];
    }

    public function serviceLinks(): HasMany
    {
        return $this->hasMany(
            ServiceFaq::class,
            'faqId',
            'faqId'
        );
    }
}
