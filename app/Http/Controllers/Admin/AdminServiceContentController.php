<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Service;
use App\Models\ServiceBenefit;
use App\Models\ServiceFaq;
use App\Models\ServiceSolution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminServiceContentController extends Controller
{
    public function storeSolution(
        Request $request,
        Service $service
    ): RedirectResponse {
        $validated = $this->validateSolution($request, $service);

        ServiceSolution::create([
            'serviceId' => $service->serviceId,
            'name' => trim($validated['name']),
            'slug' => $this->solutionSlug(
                $service,
                $validated['name'],
                $validated['slug'] ?? null
            ),
            'shortDescription' =>
                $validated['shortDescription'] ?? null,
            'description' =>
                $validated['description'] ?? null,
            'displayOrder' =>
                $validated['displayOrder'] ?? 0,
            'isPublished' => (bool) (
                $validated['isPublished'] ?? false
            ),
        ]);

        return $this->backToService(
            $service,
            'Solución agregada correctamente.'
        );
    }

    public function updateSolution(
        Request $request,
        Service $service,
        ServiceSolution $serviceSolution
    ): RedirectResponse {
        $this->assertSolutionBelongsToService(
            $service,
            $serviceSolution
        );

        $validated = $this->validateSolution(
            $request,
            $service,
            $serviceSolution
        );

        $serviceSolution->fill([
            'name' => trim($validated['name']),
            'slug' => $this->solutionSlug(
                $service,
                $validated['name'],
                $validated['slug'] ?? null,
                $serviceSolution
            ),
            'shortDescription' =>
                $validated['shortDescription'] ?? null,
            'description' =>
                $validated['description'] ?? null,
            'displayOrder' =>
                $validated['displayOrder'] ?? 0,
            'isPublished' => (bool) (
                $validated['isPublished'] ?? false
            ),
        ]);

        $serviceSolution->save();

        return $this->backToService(
            $service,
            'Solución actualizada.'
        );
    }

    public function destroySolution(
        Service $service,
        ServiceSolution $serviceSolution
    ): RedirectResponse {
        $this->assertSolutionBelongsToService(
            $service,
            $serviceSolution
        );

        $serviceSolution->delete();

        return $this->backToService(
            $service,
            'Solución eliminada.'
        );
    }

    public function storeBenefit(
        Request $request,
        Service $service
    ): RedirectResponse {
        $validated = $this->validateBenefit($request);

        ServiceBenefit::create([
            'serviceId' => $service->serviceId,
            'title' => trim($validated['title']),
            'description' =>
                $validated['description'] ?? null,
            'iconKey' =>
                $validated['iconKey'] ?? null,
            'displayOrder' =>
                $validated['displayOrder'] ?? 0,
            'isPublished' => (bool) (
                $validated['isPublished'] ?? false
            ),
        ]);

        return $this->backToService(
            $service,
            'Beneficio agregado correctamente.'
        );
    }

    public function updateBenefit(
        Request $request,
        Service $service,
        ServiceBenefit $serviceBenefit
    ): RedirectResponse {
        $this->assertBenefitBelongsToService(
            $service,
            $serviceBenefit
        );

        $validated = $this->validateBenefit($request);

        $serviceBenefit->fill([
            'title' => trim($validated['title']),
            'description' =>
                $validated['description'] ?? null,
            'iconKey' =>
                $validated['iconKey'] ?? null,
            'displayOrder' =>
                $validated['displayOrder'] ?? 0,
            'isPublished' => (bool) (
                $validated['isPublished'] ?? false
            ),
        ]);

        $serviceBenefit->save();

        return $this->backToService(
            $service,
            'Beneficio actualizado.'
        );
    }

    public function destroyBenefit(
        Service $service,
        ServiceBenefit $serviceBenefit
    ): RedirectResponse {
        $this->assertBenefitBelongsToService(
            $service,
            $serviceBenefit
        );

        $serviceBenefit->delete();

        return $this->backToService(
            $service,
            'Beneficio eliminado.'
        );
    }

    public function storeFaq(
        Request $request,
        Service $service
    ): RedirectResponse {
        $validated = $this->validateFaq($request);

        DB::transaction(function () use (
            $validated,
            $service
        ): void {
            $faq = Faq::create([
                'question' => trim(
                    $validated['question']
                ),
                'answer' => trim(
                    $validated['answer']
                ),
                'isPublished' => (bool) (
                    $validated['isPublished'] ?? false
                ),
            ]);

            ServiceFaq::create([
                'serviceId' => $service->serviceId,
                'faqId' => $faq->faqId,
                'displayOrder' =>
                    $validated['displayOrder'] ?? 0,
            ]);
        }, 3);

        return $this->backToService(
            $service,
            'Pregunta frecuente agregada.'
        );
    }

    public function updateFaq(
        Request $request,
        Service $service,
        ServiceFaq $serviceFaq
    ): RedirectResponse {
        $this->assertFaqBelongsToService(
            $service,
            $serviceFaq
        );

        $validated = $this->validateFaq($request);

        DB::transaction(function () use (
            $validated,
            $serviceFaq
        ): void {
            $faq = Faq::query()
                ->where(
                    'faqId',
                    $serviceFaq->faqId
                )
                ->firstOrFail();

            $faq->fill([
                'question' => trim(
                    $validated['question']
                ),
                'answer' => trim(
                    $validated['answer']
                ),
                'isPublished' => (bool) (
                    $validated['isPublished'] ?? false
                ),
            ]);

            $faq->save();

            $serviceFaq->displayOrder =
                $validated['displayOrder'] ?? 0;

            $serviceFaq->save();
        }, 3);

        return $this->backToService(
            $service,
            'Pregunta frecuente actualizada.'
        );
    }

    public function destroyFaq(
        Service $service,
        ServiceFaq $serviceFaq
    ): RedirectResponse {
        $this->assertFaqBelongsToService(
            $service,
            $serviceFaq
        );

        DB::transaction(function () use (
            $serviceFaq
        ): void {
            $faqId = $serviceFaq->faqId;

            $serviceFaq->delete();

            $remainingLinks = ServiceFaq::query()
                ->where('faqId', $faqId)
                ->exists();

            if (!$remainingLinks) {
                Faq::query()
                    ->where('faqId', $faqId)
                    ->delete();
            }
        }, 3);

        return $this->backToService(
            $service,
            'Pregunta frecuente eliminada.'
        );
    }

    private function validateSolution(
        Request $request,
        Service $service,
        ?ServiceSolution $serviceSolution = null
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
            'name' => [
                'required',
                'string',
                'max:190',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:190',
                Rule::unique(
                    'servicesolution',
                    'slug'
                )
                    ->where(
                        fn ($query) => $query->where(
                            'serviceId',
                            $service->serviceId
                        )
                    )
                    ->ignore(
                        $serviceSolution
                            ?->serviceSolutionId,
                        'serviceSolutionId'
                    ),
            ],
            'shortDescription' => [
                'nullable',
                'string',
                'max:500',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'displayOrder' => [
                'nullable',
                'integer',
                'min:0',
                'max:65535',
            ],
            'isPublished' => [
                'nullable',
                'boolean',
            ],
        ]);
    }

    private function validateBenefit(
        Request $request
    ): array {
        return $request->validate([
            'title' => [
                'required',
                'string',
                'max:190',
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'iconKey' => [
                'nullable',
                'string',
                'max:100',
            ],
            'displayOrder' => [
                'nullable',
                'integer',
                'min:0',
                'max:65535',
            ],
            'isPublished' => [
                'nullable',
                'boolean',
            ],
        ]);
    }

    private function validateFaq(
        Request $request
    ): array {
        return $request->validate([
            'question' => [
                'required',
                'string',
                'max:500',
            ],
            'answer' => [
                'required',
                'string',
                'max:5000',
            ],
            'displayOrder' => [
                'nullable',
                'integer',
                'min:0',
                'max:65535',
            ],
            'isPublished' => [
                'nullable',
                'boolean',
            ],
        ]);
    }

    private function solutionSlug(
        Service $service,
        string $name,
        ?string $requestedSlug = null,
        ?ServiceSolution $current = null
    ): string {
        $baseSlug = Str::slug(
            $requestedSlug ?: $name
        );

        if ($baseSlug === '') {
            $baseSlug = 'solucion';
        }

        $slug = $baseSlug;
        $suffix = 2;

        while (
            ServiceSolution::query()
                ->where(
                    'serviceId',
                    $service->serviceId
                )
                ->where('slug', $slug)
                ->when(
                    $current !== null,
                    function ($query) use (
                        $current
                    ): void {
                        $query->where(
                            'serviceSolutionId',
                            '!=',
                            $current
                                ->serviceSolutionId
                        );
                    }
                )
                ->exists()
        ) {
            $slug =
                $baseSlug
                . '-'
                . $suffix;

            $suffix++;
        }

        return $slug;
    }

    private function assertSolutionBelongsToService(
        Service $service,
        ServiceSolution $serviceSolution
    ): void {
        if (
            $serviceSolution->serviceId
            !== $service->serviceId
        ) {
            abort(404);
        }
    }

    private function assertBenefitBelongsToService(
        Service $service,
        ServiceBenefit $serviceBenefit
    ): void {
        if (
            $serviceBenefit->serviceId
            !== $service->serviceId
        ) {
            abort(404);
        }
    }

    private function assertFaqBelongsToService(
        Service $service,
        ServiceFaq $serviceFaq
    ): void {
        if (
            $serviceFaq->serviceId
            !== $service->serviceId
        ) {
            abort(404);
        }
    }

    private function backToService(
        Service $service,
        string $message
    ): RedirectResponse {
        return redirect()
            ->route(
                'admin.services.edit',
                $service
            )
            ->with(
                'success',
                $message
            );
    }
}
