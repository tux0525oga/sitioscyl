<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTag extends Model
{
    use HasUlids;

    protected $table = 'projecttag';

    protected $primaryKey = 'projectTagId';

    public $incrementing = false;

    protected $keyType = 'string';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = null;

    protected $fillable = [
        'projectId',
        'tagId',
    ];

    protected function casts(): array
    {
        return [
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

    public function tag(): BelongsTo
    {
        return $this->belongsTo(
            Tag::class,
            'tagId',
            'tagId'
        );
    }
}