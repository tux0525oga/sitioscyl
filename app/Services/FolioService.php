<?php

namespace App\Services;

use App\Models\FolioSequence;
use Illuminate\Support\Facades\DB;

class FolioService
{
    public function nextQuoteFolio(?int $year = null): string
    {
        return DB::transaction(
            fn (): string => $this->nextQuoteFolioWithinTransaction($year),
            3
        );
    }

    public function nextQuoteFolioWithinTransaction(?int $year = null): string
    {
        $year ??= (int) now()->format('Y');

        FolioSequence::query()->insertOrIgnore([
            'sequenceYear' => $year,
            'lastNumber' => 0,
            'updatedAt' => now(),
        ]);

        $sequence = FolioSequence::query()
            ->where('sequenceYear', $year)
            ->lockForUpdate()
            ->firstOrFail();

        $sequence->lastNumber++;
        $sequence->updatedAt = now();
        $sequence->save();

        return sprintf(
            'SC-%d-%05d',
            $year,
            $sequence->lastNumber
        );
    }
}