<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectComparison;
use App\Models\ProjectMedia;
use App\Models\UserAccount;
use App\Services\ProjectMediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class AdminProjectMediaController extends Controller
{
    public function store(
        Request $request,
        Project $project,
        ProjectMediaService $projectMediaService
    ): RedirectResponse {
        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:15360',
            ],
            'mediaCategoryId' => [
                'nullable',
                'string',
                Rule::exists('mediacategory', 'mediaCategoryId'),
            ],
            'title' => ['nullable', 'string', 'max:255'],
            'altText' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'displayOrder' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'isFeatured' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();

        $projectMediaService->uploadImage(
            $project,
            $request->file('file'),
            $validated,
            $user instanceof UserAccount ? $user : null
        );

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Imagen agregada correctamente.');
    }

    public function update(
        Request $request,
        Project $project,
        ProjectMedia $projectMedia,
        ProjectMediaService $projectMediaService
    ): RedirectResponse {
        $validated = $request->validate([
            'mediaCategoryId' => [
                'nullable',
                'string',
                Rule::exists('mediacategory', 'mediaCategoryId'),
            ],
            'title' => ['nullable', 'string', 'max:255'],
            'altText' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'displayOrder' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        $projectMediaService->updateMetadata(
            $project,
            $projectMedia,
            $validated
        );

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Datos de la imagen actualizados.');
    }

    public function feature(
        Project $project,
        ProjectMedia $projectMedia,
        ProjectMediaService $projectMediaService
    ): RedirectResponse {
        $projectMediaService->setFeatured(
            $project,
            $projectMedia
        );

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Imagen principal actualizada.');
    }

    public function destroy(
        Project $project,
        ProjectMedia $projectMedia,
        ProjectMediaService $projectMediaService
    ): RedirectResponse {
        try {
            $projectMediaService->removeFromProject(
                $project,
                $projectMedia
            );
        } catch (InvalidArgumentException $exception) {
            return redirect()
                ->route('admin.projects.edit', $project)
                ->withErrors([
                    'media' => $exception->getMessage(),
                ]);
        }

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Imagen retirada del proyecto.');
    }

    public function storeComparison(
        Request $request,
        Project $project
    ): RedirectResponse {
        $validated = $request->validate([
            'beforeProjectMediaId' => ['required', 'string'],
            'afterProjectMediaId' => [
                'required',
                'string',
                'different:beforeProjectMediaId',
            ],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'displayOrder' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'isPublished' => ['nullable', 'boolean'],
        ]);

        $before = ProjectMedia::query()
            ->where('projectMediaId', $validated['beforeProjectMediaId'])
            ->where('projectId', $project->projectId)
            ->firstOrFail();

        $after = ProjectMedia::query()
            ->where('projectMediaId', $validated['afterProjectMediaId'])
            ->where('projectId', $project->projectId)
            ->firstOrFail();

        $exists = ProjectComparison::query()
            ->where('projectId', $project->projectId)
            ->where('beforeMediaId', $before->mediaId)
            ->where('afterMediaId', $after->mediaId)
            ->exists();

        if ($exists) {
            return redirect()
                ->route('admin.projects.edit', $project)
                ->withErrors([
                    'comparison' => 'Esta comparación Antes/Después ya existe.',
                ]);
        }

        ProjectComparison::create([
            'projectId' => $project->projectId,
            'beforeMediaId' => $before->mediaId,
            'afterMediaId' => $after->mediaId,
            'title' => $validated['title'] ?? null,
            'description' => $validated['description'] ?? null,
            'displayOrder' => $validated['displayOrder'] ?? 0,
            'isPublished' => (bool) ($validated['isPublished'] ?? false),
        ]);

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Comparación Antes/Después creada.');
    }

    public function destroyComparison(
        Project $project,
        ProjectComparison $projectComparison
    ): RedirectResponse {
        if ($projectComparison->projectId !== $project->projectId) {
            abort(404);
        }

        $projectComparison->delete();

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Comparación eliminada.');
    }
}
