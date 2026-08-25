<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\ContactMethod;
use App\Models\PreferredTimeframe;
use App\Models\Project;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Services\QuoteRequestFileService;
use App\Services\QuoteRequestManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use InvalidArgumentException;

class PublicQuoteController extends Controller
{
    public function create(Request $request): View
    {
        $services = Service::query()
            ->where('isPublished', true)
            ->orderBy('displayOrder')
            ->orderBy('name')
            ->get();

        $projects = Project::query()
            ->where('isPublished', true)
            ->orderByDesc('isFeatured')
            ->orderBy('displayOrder')
            ->orderByDesc('createdAt')
            ->get();

        $contactMethods = ContactMethod::query()
            ->where('isActive', true)
            ->orderBy('displayOrder')
            ->orderBy('name')
            ->get();

        $preferredTimeframes = PreferredTimeframe::query()
            ->where('isActive', true)
            ->orderBy('displayOrder')
            ->orderBy('name')
            ->get();

        $selectedServiceIds = [];
        $selectedProjectId = null;

        $serviceSlug = trim(
            (string) $request->query('service', '')
        );

        if ($serviceSlug !== '') {
            $selectedService = $services->firstWhere(
                'slug',
                $serviceSlug
            );

            if ($selectedService !== null) {
                $selectedServiceIds[] =
                    $selectedService->serviceId;
            }
        }

        $projectSlug = trim(
            (string) $request->query('project', '')
        );

        if ($projectSlug !== '') {
            $selectedProject = Project::query()
                ->with('serviceLinks')
                ->where('slug', $projectSlug)
                ->where('isPublished', true)
                ->first();

            if ($selectedProject !== null) {
                $selectedProjectId =
                    $selectedProject->projectId;

                $selectedServiceIds = array_values(
                    array_unique(
                        array_merge(
                            $selectedServiceIds,
                            $selectedProject
                                ->serviceLinks
                                ->pluck('serviceId')
                                ->all()
                        )
                    )
                );
            }
        }

        return view('public.quote.create', [
            'companyProfile' => $this->companyProfile(),
            'services' => $services,
            'projects' => $projects,
            'contactMethods' => $contactMethods,
            'preferredTimeframes' => $preferredTimeframes,
            'selectedServiceIds' => $selectedServiceIds,
            'selectedProjectId' => $selectedProjectId,
            'sourceUrl' => $request->fullUrl(),
        ]);
    }

    public function store(
        Request $request,
        QuoteRequestManager $quoteRequestManager,
        QuoteRequestFileService $quoteRequestFileService
    ): RedirectResponse {
        $validated = $request->validate([
            'website' => [
                'nullable',
                'max:0',
            ],
            'serviceIds' => [
                'required',
                'array',
                'min:1',
            ],
            'serviceIds.*' => [
                'required',
                'string',
                'distinct',
                Rule::exists(
                    'service',
                    'serviceId'
                )->where(
                    fn ($query) => $query
                        ->where('isPublished', true)
                        ->whereNull('deletedAt')
                ),
            ],
            'referenceProjectId' => [
                'nullable',
                'string',
                Rule::exists(
                    'project',
                    'projectId'
                )->where(
                    fn ($query) => $query
                        ->where('isPublished', true)
                        ->whereNull('deletedAt')
                ),
            ],
            'description' => [
                'required',
                'string',
                'max:5000',
            ],
            'locationCity' => [
                'required',
                'string',
                'max:120',
            ],
            'locationState' => [
                'required',
                'string',
                'max:120',
            ],
            'locationNeighborhood' => [
                'nullable',
                'string',
                'max:160',
            ],
            'preferredTimeframeId' => [
                'nullable',
                'string',
                Rule::exists(
                    'preferredtimeframe',
                    'preferredTimeframeId'
                )->where(
                    fn ($query) =>
                        $query->where(
                            'isActive',
                            true
                        )
                ),
            ],
            'firstName' => [
                'required',
                'string',
                'max:120',
            ],
            'lastName' => [
                'nullable',
                'string',
                'max:160',
            ],
            'whatsAppNumber' => [
                'nullable',
                'string',
                'max:30',
                'required_without_all:phoneNumber,email',
            ],
            'phoneNumber' => [
                'nullable',
                'string',
                'max:30',
                'required_without_all:whatsAppNumber,email',
            ],
            'email' => [
                'nullable',
                'email',
                'max:190',
                'required_without_all:whatsAppNumber,phoneNumber',
            ],
            'preferredContactMethodId' => [
                'nullable',
                'string',
                Rule::exists(
                    'contactmethod',
                    'contactMethodId'
                )->where(
                    fn ($query) =>
                        $query->where(
                            'isActive',
                            true
                        )
                ),
            ],
            'sourceUrl' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'currentSpaceFiles' => [
                'nullable',
                'array',
                'max:6',
            ],
            'currentSpaceFiles.*' => [
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:15360',
            ],
            'referenceImageFiles' => [
                'nullable',
                'array',
                'max:6',
            ],
            'referenceImageFiles.*' => [
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:15360',
            ],
            'blueprintFiles' => [
                'nullable',
                'array',
                'max:6',
            ],
            'blueprintFiles.*' => [
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:15360',
            ],
            'sketchFiles' => [
                'nullable',
                'array',
                'max:6',
            ],
            'sketchFiles.*' => [
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:15360',
            ],
            'documentFiles' => [
                'nullable',
                'array',
                'max:6',
            ],
            'documentFiles.*' => [
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:15360',
            ],
        ]);

        try {
            $quoteRequest = $quoteRequestManager
                ->createQuoteRequest(
                    [
                        'firstName' =>
                            $validated['firstName'],
                        'lastName' =>
                            $validated['lastName']
                            ?? null,
                        'whatsAppNumber' =>
                            $validated['whatsAppNumber']
                            ?? null,
                        'phoneNumber' =>
                            $validated['phoneNumber']
                            ?? null,
                        'email' =>
                            $validated['email']
                            ?? null,
                        'preferredContactMethodId' =>
                            $validated[
                                'preferredContactMethodId'
                            ] ?? null,
                    ],
                    [
                        'description' =>
                            $validated['description'],
                        'locationCity' =>
                            $validated['locationCity'],
                        'locationState' =>
                            $validated['locationState'],
                        'locationNeighborhood' =>
                            $validated[
                                'locationNeighborhood'
                            ] ?? null,
                        'preferredTimeframeId' =>
                            $validated[
                                'preferredTimeframeId'
                            ] ?? null,
                        'referenceProjectId' =>
                            $validated[
                                'referenceProjectId'
                            ] ?? null,
                        'sourcePage' =>
                            'PublicQuoteForm',
                        'sourceUrl' =>
                            $validated['sourceUrl']
                            ?? null,
                    ],
                    $validated['serviceIds']
                );

        } catch (InvalidArgumentException $exception) {
            return back()
                ->withInput()
                ->withErrors([
                    'quote' =>
                        $exception->getMessage(),
                ]);
        }

        $fileUploadWarning = null;

        $fileGroups = [
            'currentSpaceFiles' =>
                'CurrentSpace',
            'referenceImageFiles' =>
                'ReferenceImage',
            'blueprintFiles' =>
                'Blueprint',
            'sketchFiles' =>
                'Sketch',
            'documentFiles' =>
                'Document',
        ];

        try {
            foreach (
                $fileGroups
                as $field => $categoryCode
            ) {
                $uploadedFiles = $request->file(
                    $field,
                    []
                );

                if (!is_array($uploadedFiles)) {
                    $uploadedFiles = [
                        $uploadedFiles,
                    ];
                }

                foreach ($uploadedFiles as $file) {
                    if ($file === null) {
                        continue;
                    }

                    $quoteRequestFileService
                        ->attachPrivateFile(
                            $quoteRequest
                                ->quoteRequestId,
                            $file,
                            $categoryCode
                        );
                }
            }
        } catch (InvalidArgumentException) {
            $fileUploadWarning =
                'Tu solicitud quedó registrada, pero uno o más archivos no pudieron adjuntarse. Conserva tu folio para dar seguimiento.';
        }

        return redirect()
            ->route(
                'public.quote.thanks',
                [
                    'quoteRequest' =>
                        $quoteRequest->folio,
                ]
            )
            ->with(
                'fileUploadWarning',
                $fileUploadWarning
            );
    }

    public function thanks(
        QuoteRequest $quoteRequest
    ): View {
        return view('public.quote.thanks', [
            'companyProfile' => $this->companyProfile(),
            'folio' => $quoteRequest->folio,
        ]);
    }

    private function companyProfile(): ?CompanyProfile
    {
        return CompanyProfile::query()
            ->where('code', 'Main')
            ->first();
    }
}
