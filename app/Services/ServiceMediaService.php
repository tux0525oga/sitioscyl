<?php

namespace App\Services;

use App\Models\MediaAsset;
use App\Models\Service;
use App\Models\ServiceMedia;
use App\Models\UserAccount;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Throwable;

class ServiceMediaService
{
    private const STORAGE_DISK = 'public';

    private const MAX_FILE_SIZE_BYTES =
        15 * 1024 * 1024;

    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    public function uploadImage(
        Service $service,
        UploadedFile $file,
        array $metadata,
        ?UserAccount $uploadedBy = null
    ): ServiceMedia {
        $this->validateImage($file);

        $realPath = $file->getRealPath();

        if ($realPath === false) {
            throw new InvalidArgumentException(
                'No se pudo leer la imagen.'
            );
        }

        $imageSize = getimagesize($realPath);

        if ($imageSize === false) {
            throw new InvalidArgumentException(
                'No se pudieron obtener las dimensiones.'
            );
        }

        $mimeType = $file->getMimeType();

        if ($mimeType === null) {
            throw new InvalidArgumentException(
                'No se pudo determinar el tipo de imagen.'
            );
        }

        $extension = $this->resolveExtension($file);

        $storedFileName =
            (string) Str::ulid()
            . '.'
            . $extension;

        $directory = sprintf(
            'services/%s',
            $service->serviceId
        );

        $storagePath =
            $directory
            . '/'
            . $storedFileName;

        $sha256 = hash_file(
            'sha256',
            $realPath
        );

        if ($sha256 === false) {
            throw new InvalidArgumentException(
                'No se pudo calcular la huella de la imagen.'
            );
        }

        $storedPath = Storage::disk(
            self::STORAGE_DISK
        )->putFileAs(
            $directory,
            $file,
            $storedFileName
        );

        if ($storedPath === false) {
            throw new InvalidArgumentException(
                'No se pudo almacenar la imagen.'
            );
        }

        try {
            return DB::transaction(
                function () use (
                    $service,
                    $file,
                    $metadata,
                    $uploadedBy,
                    $storedFileName,
                    $storagePath,
                    $mimeType,
                    $extension,
                    $sha256,
                    $imageSize
                ): ServiceMedia {
                    $mediaAsset = MediaAsset::create([
                        'uploadedBy' =>
                            $uploadedBy?->userId,
                        'storageDisk' =>
                            self::STORAGE_DISK,
                        'storagePath' =>
                            $storagePath,
                        'fileName' =>
                            $storedFileName,
                        'originalFileName' =>
                            $file
                                ->getClientOriginalName(),
                        'mimeType' =>
                            $mimeType,
                        'fileExtension' =>
                            $extension,
                        'fileSize' =>
                            $file->getSize(),
                        'width' =>
                            $imageSize[0],
                        'height' =>
                            $imageSize[1],
                        'sha256' =>
                            $sha256,
                        'title' =>
                            $metadata['title']
                            ?? null,
                        'altText' =>
                            $metadata['altText']
                            ?? null,
                        'description' =>
                            $metadata['description']
                            ?? null,
                        'isPublic' => true,
                        'isPublished' => true,
                    ]);

                    $serviceMedia = ServiceMedia::create([
                        'serviceId' =>
                            $service->serviceId,
                        'mediaId' =>
                            $mediaAsset->mediaId,
                        'mediaCategoryId' =>
                            $metadata[
                                'mediaCategoryId'
                            ] ?? null,
                        'displayOrder' =>
                            $metadata[
                                'displayOrder'
                            ] ?? 0,
                        'isFeatured' => false,
                    ]);

                    if (
                        (bool) (
                            $metadata['isFeatured']
                            ?? false
                        ) ||
                        $service->featuredImageId
                            === null
                    ) {
                        $this->setFeatured(
                            $service,
                            $serviceMedia
                        );
                    }

                    return $serviceMedia->fresh();
                },
                3
            );
        } catch (Throwable $exception) {
            Storage::disk(
                self::STORAGE_DISK
            )->delete($storagePath);

            throw $exception;
        }
    }

    public function setFeatured(
        Service $service,
        ServiceMedia $serviceMedia
    ): void {
        $this->assertBelongs(
            $service,
            $serviceMedia
        );

        DB::transaction(
            function () use (
                $service,
                $serviceMedia
            ): void {
                ServiceMedia::query()
                    ->where(
                        'serviceId',
                        $service->serviceId
                    )
                    ->update([
                        'isFeatured' => false,
                    ]);

                $serviceMedia->isFeatured = true;
                $serviceMedia->save();

                $service->featuredImageId =
                    $serviceMedia->mediaId;

                $service->save();
            },
            3
        );
    }

    public function remove(
        Service $service,
        ServiceMedia $serviceMedia
    ): void {
        $this->assertBelongs(
            $service,
            $serviceMedia
        );

        DB::transaction(
            function () use (
                $service,
                $serviceMedia
            ): void {
                $wasFeatured =
                    $service->featuredImageId
                    === $serviceMedia->mediaId;

                $serviceMedia->delete();

                if ($wasFeatured) {
                    $service->featuredImageId = null;
                    $service->save();

                    $next = ServiceMedia::query()
                        ->where(
                            'serviceId',
                            $service->serviceId
                        )
                        ->orderBy('displayOrder')
                        ->orderBy('createdAt')
                        ->first();

                    if ($next !== null) {
                        $this->setFeatured(
                            $service,
                            $next
                        );
                    }
                }
            },
            3
        );
    }

    private function assertBelongs(
        Service $service,
        ServiceMedia $serviceMedia
    ): void {
        if (
            $serviceMedia->serviceId
            !== $service->serviceId
        ) {
            abort(404);
        }
    }

    private function validateImage(
        UploadedFile $file
    ): void {
        if (!$file->isValid()) {
            throw new InvalidArgumentException(
                'La imagen cargada no es válida.'
            );
        }

        $size = $file->getSize();

        if (
            $size === false ||
            $size > self::MAX_FILE_SIZE_BYTES
        ) {
            throw new InvalidArgumentException(
                'La imagen excede 15 MB.'
            );
        }

        $mimeType = $file->getMimeType();

        if (
            $mimeType === null ||
            !in_array(
                $mimeType,
                self::ALLOWED_MIME_TYPES,
                true
            )
        ) {
            throw new InvalidArgumentException(
                'Solo se permiten JPG, PNG o WEBP.'
            );
        }
    }

    private function resolveExtension(
        UploadedFile $file
    ): string {
        return match (
            $file->getMimeType()
        ) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => throw new InvalidArgumentException(
                'Extensión de imagen no permitida.'
            ),
        };
    }
}
