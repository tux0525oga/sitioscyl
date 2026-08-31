<?php

namespace App\Services;

use App\Models\CompanyProfile;
use App\Models\MediaAsset;
use App\Models\UserAccount;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Throwable;

class CompanyIdentityMediaService
{
    private const STORAGE_DISK = 'public';

    private const MAX_FILE_SIZE_BYTES =
        15 * 1024 * 1024;

    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    public function uploadLogo(
        CompanyProfile $companyProfile,
        UploadedFile $file,
        ?UserAccount $uploadedBy = null
    ): MediaAsset {
        return $this->uploadIdentityImage(
            $companyProfile,
            $file,
            'logoMediaId',
            'Logotipo Somos Constructivos',
            'Logotipo de Somos Constructivos',
            'Activo de identidad visual corporativa.',
            $uploadedBy
        );
    }

    public function uploadMonogram(
        CompanyProfile $companyProfile,
        UploadedFile $file,
        ?UserAccount $uploadedBy = null
    ): MediaAsset {
        return $this->uploadIdentityImage(
            $companyProfile,
            $file,
            'monogramMediaId',
            'Monograma Somos Constructivos',
            'Monograma de Somos Constructivos',
            'Activo de identidad visual corporativa.',
            $uploadedBy
        );
    }

    public function uploadHomeHero(
        CompanyProfile $companyProfile,
        UploadedFile $file,
        ?UserAccount $uploadedBy = null
    ): MediaAsset {
        return $this->uploadIdentityImage(
            $companyProfile,
            $file,
            'homeHeroMediaId',
            'Portada de inicio Somos Constructivos',
            'Imagen principal de Somos Constructivos',
            'Imagen principal de portada del sitio público.',
            $uploadedBy
        );
    }

    private function uploadIdentityImage(
        CompanyProfile $companyProfile,
        UploadedFile $file,
        string $profileField,
        string $title,
        string $altText,
        string $description,
        ?UserAccount $uploadedBy
    ): MediaAsset {
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
                'No se pudieron obtener las dimensiones de la imagen.'
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
            'company/%s/identity',
            $companyProfile->companyProfileId
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
                    $companyProfile,
                    $file,
                    $profileField,
                    $title,
                    $altText,
                    $description,
                    $uploadedBy,
                    $storedFileName,
                    $storagePath,
                    $mimeType,
                    $extension,
                    $sha256,
                    $imageSize
                ): MediaAsset {
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
                            $file->getClientOriginalName(),
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
                            $title,
                        'altText' =>
                            $altText,
                        'description' =>
                            $description,
                        'isPublic' =>
                            true,
                        'isPublished' =>
                            true,
                    ]);

                    $companyProfile->{$profileField} =
                        $mediaAsset->mediaId;

                    $companyProfile->save();

                    return $mediaAsset;
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

    private function validateImage(
        UploadedFile $file
    ): void {
        if (!$file->isValid()) {
            throw new InvalidArgumentException(
                'La carga de la imagen no es válida.'
            );
        }

        $fileSize = $file->getSize();

        if (
            $fileSize === false
            || $fileSize <= 0
            || $fileSize > self::MAX_FILE_SIZE_BYTES
        ) {
            throw new InvalidArgumentException(
                'La imagen supera el tamaño permitido de 15 MB.'
            );
        }

        $mimeType = $file->getMimeType();

        if (
            $mimeType === null
            || !in_array(
                $mimeType,
                self::ALLOWED_MIME_TYPES,
                true
            )
        ) {
            throw new InvalidArgumentException(
                'El formato de imagen no está permitido.'
            );
        }
    }

    private function resolveExtension(
        UploadedFile $file
    ): string {
        return match ($file->getMimeType()) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => throw new InvalidArgumentException(
                'No se pudo determinar una extensión válida.'
            ),
        };
    }
}
