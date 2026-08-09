<?php

namespace App\Services;

use App\Models\QuoteFileCategory;
use App\Models\QuoteRequest;
use App\Models\QuoteRequestFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Throwable;

class QuoteRequestFileService
{
    private const STORAGE_DISK = 'local';

    private const MAX_FILE_SIZE_BYTES = 15 * 1024 * 1024;

    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'application/pdf',
    ];

    public function attachPrivateFile(
        string $quoteRequestId,
        UploadedFile $file,
        ?string $categoryCode = null
    ): QuoteRequestFile {
        $quoteRequest = QuoteRequest::query()
            ->where('quoteRequestId', $quoteRequestId)
            ->firstOrFail();

        $this->validateUploadedFile($file);

        $category = $this->resolveCategory($categoryCode);

        $originalFileName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType() ?: 'application/octet-stream';
        $extension = $this->resolveExtension($file);

        $storedFileName = (string) Str::ulid();

        if ($extension !== '') {
            $storedFileName .= '.' . $extension;
        }

        $directory = sprintf(
            'quoteRequests/%s',
            $quoteRequest->quoteRequestId
        );

        $storagePath = $directory . '/' . $storedFileName;

        $realPath = $file->getRealPath();

        if ($realPath === false) {
            throw new InvalidArgumentException(
                'No se pudo leer el archivo cargado.'
            );
        }

        $sha256 = hash_file('sha256', $realPath);

        if ($sha256 === false) {
            throw new InvalidArgumentException(
                'No se pudo calcular la huella del archivo.'
            );
        }

        $storedPath = Storage::disk(self::STORAGE_DISK)->putFileAs(
            $directory,
            $file,
            $storedFileName
        );

        if ($storedPath === false) {
            throw new InvalidArgumentException(
                'No se pudo almacenar el archivo.'
            );
        }

        try {
            return DB::transaction(function () use (
                $quoteRequest,
                $category,
                $storedFileName,
                $originalFileName,
                $mimeType,
                $file,
                $sha256,
                $storagePath
            ): QuoteRequestFile {
                return QuoteRequestFile::create([
                    'quoteRequestId' => $quoteRequest->quoteRequestId,
                    'quoteFileCategoryId' => $category?->quoteFileCategoryId,
                    'storageDisk' => self::STORAGE_DISK,
                    'storagePath' => $storagePath,
                    'fileName' => $storedFileName,
                    'originalFileName' => $originalFileName,
                    'mimeType' => $mimeType,
                    'fileSize' => $file->getSize(),
                    'sha256' => $sha256,
                ]);
            }, 3);
        } catch (Throwable $exception) {
            Storage::disk(self::STORAGE_DISK)->delete($storagePath);

            throw $exception;
        }
    }

    public function deletePrivateFile(
        QuoteRequestFile $quoteRequestFile
    ): void {
        DB::transaction(function () use ($quoteRequestFile): void {
            $storageDisk = $quoteRequestFile->storageDisk;
            $storagePath = $quoteRequestFile->storagePath;

            $quoteRequestFile->delete();

            Storage::disk($storageDisk)->delete($storagePath);
        }, 3);
    }

    private function resolveCategory(
        ?string $categoryCode
    ): ?QuoteFileCategory {
        if ($categoryCode === null || $categoryCode === '') {
            return null;
        }

        return QuoteFileCategory::query()
            ->where('code', $categoryCode)
            ->where('isActive', true)
            ->firstOrFail();
    }

    private function validateUploadedFile(UploadedFile $file): void
    {
        if (!$file->isValid()) {
            throw new InvalidArgumentException(
                'El archivo cargado no es válido.'
            );
        }

        $fileSize = $file->getSize();

        if ($fileSize === false || $fileSize > self::MAX_FILE_SIZE_BYTES) {
            throw new InvalidArgumentException(
                'El archivo excede el tamaño máximo permitido de 15 MB.'
            );
        }

        $mimeType = $file->getMimeType();

        if (
            $mimeType === null ||
            !in_array($mimeType, self::ALLOWED_MIME_TYPES, true)
        ) {
            throw new InvalidArgumentException(
                'El tipo de archivo no está permitido.'
            );
        }
    }

    private function resolveExtension(UploadedFile $file): string
    {
        $extension = strtolower(
            $file->guessExtension()
                ?: $file->getClientOriginalExtension()
        );

        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png',
            'webp',
            'pdf',
        ];

        if (!in_array($extension, $allowedExtensions, true)) {
            throw new InvalidArgumentException(
                'La extensión del archivo no está permitida.'
            );
        }

        return $extension;
    }
}
