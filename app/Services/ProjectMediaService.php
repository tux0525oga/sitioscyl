<?php

namespace App\Services;

use App\Models\MediaAsset;
use App\Models\Project;
use App\Models\ProjectMedia;
use App\Models\UserAccount;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Throwable;

class ProjectMediaService
{
    private const STORAGE_DISK = 'public';
    private const MAX_FILE_SIZE_BYTES = 15 * 1024 * 1024;

    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    public function uploadImage(
        Project $project,
        UploadedFile $file,
        array $metadata,
        ?UserAccount $uploadedBy = null
    ): ProjectMedia {
        $this->validateImage($file);

        $realPath = $file->getRealPath();

        if ($realPath === false) {
            throw new InvalidArgumentException(
                'No se pudo leer el archivo cargado.'
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
        $storedFileName = (string) Str::ulid() . '.' . $extension;

        $directory = sprintf(
            'projects/%s',
            $project->projectId
        );

        $storagePath = $directory . '/' . $storedFileName;

        $sha256 = hash_file('sha256', $realPath);

        if ($sha256 === false) {
            throw new InvalidArgumentException(
                'No se pudo calcular la huella de la imagen.'
            );
        }

        $storedPath = Storage::disk(self::STORAGE_DISK)
            ->putFileAs(
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
            return DB::transaction(function () use (
                $project,
                $file,
                $metadata,
                $uploadedBy,
                $storedFileName,
                $storagePath,
                $mimeType,
                $extension,
                $sha256,
                $imageSize
            ): ProjectMedia {
                $mediaAsset = MediaAsset::create([
                    'uploadedBy' => $uploadedBy?->userId,
                    'storageDisk' => self::STORAGE_DISK,
                    'storagePath' => $storagePath,
                    'fileName' => $storedFileName,
                    'originalFileName' => $file->getClientOriginalName(),
                    'mimeType' => $mimeType,
                    'fileExtension' => $extension,
                    'fileSize' => $file->getSize(),
                    'width' => $imageSize[0],
                    'height' => $imageSize[1],
                    'sha256' => $sha256,
                    'title' => $metadata['title'] ?? null,
                    'altText' => $metadata['altText'] ?? null,
                    'description' => $metadata['description'] ?? null,
                    'isPublic' => true,
                    'isPublished' => true,
                ]);

                $projectMedia = ProjectMedia::create([
                    'projectId' => $project->projectId,
                    'mediaId' => $mediaAsset->mediaId,
                    'mediaCategoryId' => $metadata['mediaCategoryId'] ?? null,
                    'displayOrder' => $metadata['displayOrder'] ?? 0,
                    'isFeatured' => false,
                ]);

                if (
                    (bool) ($metadata['isFeatured'] ?? false) ||
                    $project->featuredImageId === null
                ) {
                    $this->setFeatured($project, $projectMedia);
                }

                return $projectMedia->fresh([
                    'mediaAsset',
                    'mediaCategory',
                ]);
            }, 3);
        } catch (Throwable $exception) {
            Storage::disk(self::STORAGE_DISK)
                ->delete($storagePath);

            throw $exception;
        }
    }

    public function updateMetadata(
        Project $project,
        ProjectMedia $projectMedia,
        array $metadata
    ): ProjectMedia {
        $this->assertBelongsToProject($project, $projectMedia);

        return DB::transaction(function () use (
            $projectMedia,
            $metadata
        ): ProjectMedia {
            $projectMedia->mediaCategoryId =
                $metadata['mediaCategoryId'] ?? null;

            $projectMedia->displayOrder =
                $metadata['displayOrder'] ?? 0;

            $projectMedia->save();

            $projectMedia->mediaAsset->fill([
                'title' => $metadata['title'] ?? null,
                'altText' => $metadata['altText'] ?? null,
                'description' => $metadata['description'] ?? null,
            ]);

            $projectMedia->mediaAsset->save();

            return $projectMedia->fresh([
                'mediaAsset',
                'mediaCategory',
            ]);
        }, 3);
    }

    public function setFeatured(
        Project $project,
        ProjectMedia $projectMedia
    ): void {
        $this->assertBelongsToProject($project, $projectMedia);

        DB::transaction(function () use (
            $project,
            $projectMedia
        ): void {
            ProjectMedia::query()
                ->where('projectId', $project->projectId)
                ->update([
                    'isFeatured' => false,
                ]);

            $projectMedia->isFeatured = true;
            $projectMedia->save();

            $project->featuredImageId = $projectMedia->mediaId;
            $project->save();
        }, 3);
    }

    public function removeFromProject(
        Project $project,
        ProjectMedia $projectMedia
    ): void {
        $this->assertBelongsToProject($project, $projectMedia);

        DB::transaction(function () use (
            $project,
            $projectMedia
        ): void {
            $mediaId = $projectMedia->mediaId;

            $isUsedInComparison = $project
                ->comparisons()
                ->where(function ($query) use ($mediaId): void {
                    $query
                        ->where('beforeMediaId', $mediaId)
                        ->orWhere('afterMediaId', $mediaId);
                })
                ->exists();

            if ($isUsedInComparison) {
                throw new InvalidArgumentException(
                    'La imagen participa en una comparación Antes/Después. Elimina primero esa comparación.'
                );
            }

            $wasFeatured = $project->featuredImageId === $mediaId;

            $projectMedia->delete();

            if ($wasFeatured) {
                $project->featuredImageId = null;
                $project->save();

                $nextMedia = ProjectMedia::query()
                    ->where('projectId', $project->projectId)
                    ->orderBy('displayOrder')
                    ->orderBy('createdAt')
                    ->first();

                if ($nextMedia !== null) {
                    $this->setFeatured($project, $nextMedia);
                }
            }
        }, 3);
    }

    private function assertBelongsToProject(
        Project $project,
        ProjectMedia $projectMedia
    ): void {
        if ($projectMedia->projectId !== $project->projectId) {
            throw new InvalidArgumentException(
                'La imagen no pertenece a este proyecto.'
            );
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

        $fileSize = $file->getSize();

        if (
            $fileSize === false ||
            $fileSize > self::MAX_FILE_SIZE_BYTES
        ) {
            throw new InvalidArgumentException(
                'La imagen excede el tamaño máximo permitido de 15 MB.'
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
                'Solo se permiten imágenes JPG, PNG o WEBP.'
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
                'No se pudo determinar una extensión segura para la imagen.'
            ),
        };
    }
}
