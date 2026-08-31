<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FolioSequence extends Model
{
    protected $table = 'foliosequence';

    protected $primaryKey = 'sequenceYear';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'sequenceYear',
        'lastNumber',
        'updatedAt',
    ];

    protected function casts(): array
    {
        return [
            'sequenceYear' => 'integer',
            'lastNumber' => 'integer',
            'updatedAt' => 'datetime',
        ];
    }
}