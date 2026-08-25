<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Models\Faq;
use App\Models\MediaAsset;
use App\Models\MediaCategory;
use App\Models\ServiceBenefit;
use App\Models\ServiceFaq;
use App\Models\ServiceMedia;
use App\Models\ServiceSolution;


class AdminServiceController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $services = Service::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('shortDescription', 'like', "%{$search}%");
                });
            })
            ->orderBy('displayOrder')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.services.index', [
            'services' => $services,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateService($request);
        $service = Service::create($this->attributes($validated));

        return redirect()
            ->route('admin.services.edit', $service)
            ->with('success', 'Servicio creado correctamente.');
    }

public function edit(Service $service): View
{
    $serviceSolutions = ServiceSolution::query()
        ->where('serviceId', $service->serviceId)
        ->orderBy('displayOrder')
        ->orderBy('name')
        ->get();

    $serviceBenefits = ServiceBenefit::query()
        ->where('serviceId', $service->serviceId)
        ->orderBy('displayOrder')
        ->orderBy('title')
        ->get();

    $serviceFaqLinks = ServiceFaq::query()
        ->where('serviceId', $service->serviceId)
        ->orderBy('displayOrder')
        ->get();

    $serviceFaqMap = Faq::query()
        ->whereIn(
            'faqId',
            $serviceFaqLinks->pluck('faqId')
        )
        ->get()
        ->keyBy('faqId');

    $serviceMediaItems = ServiceMedia::query()
        ->where('serviceId', $service->serviceId)
        ->orderBy('displayOrder')
        ->orderBy('createdAt')
        ->get();

    $serviceMediaMap = MediaAsset::query()
        ->whereIn(
            'mediaId',
            $serviceMediaItems->pluck('mediaId')
        )
        ->get()
        ->keyBy('mediaId');

    $mediaCategories = MediaCategory::query()
        ->where('isActive', true)
        ->orderBy('displayOrder')
        ->orderBy('name')
        ->get();

    return view('admin.services.edit', [
        'service' => $service,
        'serviceSolutions' => $serviceSolutions,
        'serviceBenefits' => $serviceBenefits,
        'serviceFaqLinks' => $serviceFaqLinks,
        'serviceFaqMap' => $serviceFaqMap,
        'serviceMediaItems' => $serviceMediaItems,
        'serviceMediaMap' => $serviceMediaMap,
        'mediaCategories' => $mediaCategories,
        'serviceMediaCategoryMap' => $mediaCategories
            ->keyBy('mediaCategoryId'),
    ]);
}


    public function update(
        Request $request,
        Service $service
    ): RedirectResponse {
        $validated = $this->validateService($request, $service);

        $service->fill($this->attributes($validated, $service));
        $service->save();

        return redirect()
            ->route('admin.services.edit', $service)
            ->with('success', 'Servicio actualizado correctamente.');
    }

    private function validateService(
        Request $request,
        ?Service $service = null
    ): array {
        $requestedSlug = trim((string) $request->input('slug', ''));

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
                Rule::unique('service', 'slug')
                    ->ignore($service?->serviceId, 'serviceId'),
            ],
            'shortDescription' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'heroTitle' => ['nullable', 'string', 'max:255'],
            'heroSubtitle' => ['nullable', 'string', 'max:500'],
            'displayOrder' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'isFeatured' => ['nullable', 'boolean'],
            'isPublished' => ['nullable', 'boolean'],
        ]);
    }

    private function attributes(
        array $validated,
        ?Service $service = null
    ): array {
        $slug = trim((string) ($validated['slug'] ?? ''));

        if ($slug === '') {
            $slug = $this->uniqueSlug($validated['name'], $service);
        }

        $isPublished = (bool) ($validated['isPublished'] ?? false);
        $publishedAt = $service?->publishedAt;

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
            'heroTitle' => $validated['heroTitle'] ?? null,
            'heroSubtitle' => $validated['heroSubtitle'] ?? null,
            'displayOrder' => $validated['displayOrder'] ?? 0,
            'isFeatured' => (bool) ($validated['isFeatured'] ?? false),
            'isPublished' => $isPublished,
            'publishedAt' => $publishedAt,
        ];
    }

    private function uniqueSlug(
        string $name,
        ?Service $service = null
    ): string {
        $baseSlug = Str::slug($name) ?: 'servicio';
        $slug = $baseSlug;
        $suffix = 2;

        while (
            Service::query()
                ->where('slug', $slug)
                ->when($service !== null, function ($query) use ($service): void {
                    $query->where('serviceId', '!=', $service->serviceId);
                })
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }
}
