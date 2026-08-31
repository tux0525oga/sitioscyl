<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequestFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PrivateQuoteRequestFileController extends Controller
{
    public function show(
        QuoteRequestFile $quoteRequestFile
    ): StreamedResponse {
        Gate::authorize(
            'view',
            $quoteRequestFile
        );

        $disk = Storage::disk(
            $quoteRequestFile->storageDisk
        );

        abort_unless(
            $disk->exists($quoteRequestFile->storagePath),
            404
        );

        return $disk->response(
            $quoteRequestFile->storagePath,
            $quoteRequestFile->originalFileName
                ?: $quoteRequestFile->fileName,
            [
                'Content-Type' =>
                    $quoteRequestFile->mimeType,

                'Cache-Control' =>
                    'private, no-store, max-age=0',

                'X-Content-Type-Options' =>
                    'nosniff',
            ],
            'inline'
        );
    }

    public function download(
        QuoteRequestFile $quoteRequestFile
    ): StreamedResponse {
        Gate::authorize(
            'view',
            $quoteRequestFile
        );

        $disk = Storage::disk(
            $quoteRequestFile->storageDisk
        );

        abort_unless(
            $disk->exists($quoteRequestFile->storagePath),
            404
        );

        return $disk->download(
            $quoteRequestFile->storagePath,
            $quoteRequestFile->originalFileName
                ?: $quoteRequestFile->fileName
        );
    }
}