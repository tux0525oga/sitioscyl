<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectService;
use App\Models\ProjectTag;
use App\Models\Service;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Models\MediaAsset;
use App\Models\MediaCategory;

class AdminProjectController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $projects = Project::query()
            ->with(['serviceLinks.service', 'tagLinks.tag'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('locationCity', 'like', "%{$search}%")
                        ->orWhere('locationState', 'like', "%{$search}%");
                });
            })
            ->orderBy('displayOrder')
            ->orderByDesc('createdAt')
            ->paginate(20)
            ->withQueryString();

        return view('admin.projects.index', [
            'projects' => $projects,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.create', $this->formOptions());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProject($request);

        $project = DB::transaction(function () use ($validated): Project {
            $project = Project::create($this->projectAttributes($validated));

            $this->replaceServiceLinks(
                $project,
                $validated['serviceIds'] ?? []
            );

            $this->replaceTagLinks(
                $project,
                $validated['tagIds'] ?? []
            );

            $this->saveSeo($project, $validated);

            return $project;
        }, 3);

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Proyecto creado correctamente.');
    }

    public function edit(Project $project): View
    {
        $project->load([
            'seo',
            'serviceLinks',
            'tagLinks',
            'mediaLinks.mediaAsset',
            'mediaLinks.mediaCategory',
            'comparisons.beforeImage',
            'comparisons.afterImage',
        ]);

        return view('admin.projects.edit', array_merge(
            $this->formOptions(),
            ['project' => $project]
        ));
    }

    public function update(
        Request $request,
        Project $project
    ): RedirectResponse {
        $validated = $this->validateProject($request, $project);

        DB::transaction(function () use ($project, $validated): void {
            $project->fill($this->projectAttributes($validated, $project));
            $project->save();

            $this->replaceServiceLinks(
                $project,
                $validated['serviceIds'] ?? []
            );

            $this->replaceTagLinks(
                $project,
                $validated['tagIds'] ?? []
            );

            $this->saveSeo($project, $validated);
        }, 3);

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Proyecto actualizado correctamente.');
    }

    private function validateProject(
        Request $request,
        ?Project $project = null
    ): array {
        $requestedSlug = trim(
            (string) $request->input('slug', '')
        );

        $request->merge([
            'slug' => $requestedSlug !== ''
                ? Str::slug($requestedSlug)
                : null,
        ]);

        return $request->validate([
            'name' => ['required', 'string', 'max:190'],
            'slug' => [
                'nullable',
                'string',
                'max:190',
                Rule::unique('project', 'slug')
                    ->ignore($project?->projectId, 'projectId'),
            ],
            'shortDescription' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'challengeDescription' => ['nullable', 'string'],
            'solutionDescription' => ['nullable', 'string'],
            'locationCity' => ['nullable', 'string', 'max:120'],
            'locationState' => ['nullable', 'string', 'max:120'],
            'projectYear' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'displayOrder' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'isFeatured' => ['nullable', 'boolean'],
            'isPublished' => ['nullable', 'boolean'],
            'serviceIds' => ['nullable', 'array'],
            'serviceIds.*' => [
                'string',
                'distinct',
                Rule::exists('service', 'serviceId'),
            ],
            'tagIds' => ['nullable', 'array'],
            'tagIds.*' => [
                'string',
                'distinct',
                Rule::exists('tag', 'tagId'),
            ],

            'seo' => ['nullable', 'array'],
            'seo.metaTitle' => ['nullable', 'string', 'max:180'],
            'seo.metaDescription' => ['nullable', 'string', 'max:320'],
            'seo.canonicalUrl' => ['nullable', 'string', 'max:500'],
            'seo.socialTitle' => ['nullable', 'string', 'max:180'],
            'seo.socialDescription' => ['nullable', 'string', 'max:320'],
            'seo.socialImageId' => [
                'nullable',
                'string',
                Rule::exists('mediaasset', 'mediaId')
                    ->where(function ($query): void {
                        $query
                            ->where('isPublic', true)
                            ->where('isPublished', true)
                            ->where('mimeType', 'like', 'image/%');
                    }),
            ],
            'seo.robotsIndex' => ['nullable', 'boolean'],
            'seo.robotsFollow' => ['nullable', 'boolean'],
        ]);
    }

    private function projectAttributes(
        array $validated,
        ?Project $project = null
    ): array {
        $slug = trim((string) ($validated['slug'] ?? ''));

        if ($slug === '') {
            $slug = $this->uniqueSlug($validated['name'], $project);
        } else {
            $slug = Str::slug($slug);
        }

        $isPublished = (bool) ($validated['isPublished'] ?? false);
        $publishedAt = $project?->publishedAt;

        if ($isPublished && $publishedAt === null) {
            $publishedAt = now();
        }

        if (!$isPublished) {
            $publishedAt = null;
        }

        return [
            'name' => trim($validated['name']),
            'slug' => $slug,
            'shortDescription' => $validated['shortDescription'] ?? null,
            'description' => $validated['description'] ?? null,
            'challengeDescription' => $validated['challengeDescription'] ?? null,
            'solutionDescription' => $validated['solutionDescription'] ?? null,
            'locationCity' => $validated['locationCity'] ?? null,
            'locationState' => $validated['locationState'] ?? null,
            'projectYear' => $validated['projectYear'] ?? null,
            'displayOrder' => $validated['displayOrder'] ?? 0,
            'isFeatured' => (bool) ($validated['isFeatured'] ?? false),
            'isPublished' => $isPublished,
            'publishedAt' => $publishedAt,
        ];
    }

    private function uniqueSlug(
        string $name,
        ?Project $project = null
    ): string {
        $baseSlug = Str::slug($name) ?: 'proyecto';
        $slug = $baseSlug;
        $suffix = 2;

        while (
            Project::query()
                ->where('slug', $slug)
                ->when($project !== null, function ($query) use ($project): void {
                    $query->where('projectId', '!=', $project->projectId);
                })
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    private function replaceServiceLinks(
        Project $project,
        array $serviceIds
    ): void {
        ProjectService::query()
            ->where('projectId', $project->projectId)
            ->delete();

        foreach (array_values(array_unique($serviceIds)) as $index => $serviceId) {
            ProjectService::create([
                'projectId' => $project->projectId,
                'serviceId' => $serviceId,
                'displayOrder' => $index + 1,
            ]);
        }
    }

    private function replaceTagLinks(
        Project $project,
        array $tagIds
    ): void {
        ProjectTag::query()
            ->where('projectId', $project->projectId)
            ->delete();

        foreach (array_values(array_unique($tagIds)) as $tagId) {
            ProjectTag::create([
                'projectId' => $project->projectId,
                'tagId' => $tagId,
            ]);
        }
    }

    private function formOptions(): array
    {
        return [
            'services' => Service::query()
                ->orderBy('displayOrder')
                ->orderBy('name')
                ->get(),
            'tags' => Tag::query()
                ->where('isActive', true)
                ->orderBy('name')
                ->get(),
            'mediaCategories' => MediaCategory::query()
                ->where('isActive', true)
                ->orderBy('displayOrder')
                ->orderBy('name')
                ->get(),

            'seoMediaAssets' => MediaAsset::query()
                ->where('isPublic', true)
                ->where('isPublished', true)
                ->where('mimeType', 'like', 'image/%')
                ->orderByDesc('createdAt')
                ->get(),
        ];
    }

    private function saveSeo(
        Project $project,
        array $validated
    ): void {
        $seo = $validated['seo'] ?? [];

        $project->seo()->updateOrCreate(
            [
                'projectId' => $project->projectId,
            ],
            [
                'metaTitle' => $this->nullableTrim(
                    $seo['metaTitle'] ?? null
                ),
                'metaDescription' => $this->nullableTrim(
                    $seo['metaDescription'] ?? null
                ),
                'canonicalUrl' => $this->nullableTrim(
                    $seo['canonicalUrl'] ?? null
                ),
                'socialTitle' => $this->nullableTrim(
                    $seo['socialTitle'] ?? null
                ),
                'socialDescription' => $this->nullableTrim(
                    $seo['socialDescription'] ?? null
                ),
                'socialImageId' => $this->nullableTrim(
                    $seo['socialImageId'] ?? null
                ),
                'robotsIndex' => (bool) (
                    $seo['robotsIndex'] ?? true
                ),
                'robotsFollow' => (bool) (
                    $seo['robotsFollow'] ?? true
                ),
            ]
        );
    }

    private function nullableTrim(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value !== ''
            ? $value
            : null;
    }
}
