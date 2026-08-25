<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Faq;
use App\Models\MediaAsset;
use App\Models\Service;
use App\Models\ServiceBenefit;
use App\Models\ServiceFaq;
use App\Models\ServiceMedia;
use App\Models\ServiceSolution;
use Illuminate\View\View;

class PublicServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::query()
            ->with('featuredImage')
            ->where('isPublished', true)
            ->orderBy('displayOrder')
            ->orderBy('name')
            ->get();

        return view('public.services.index', [
            'companyProfile' => $this->companyProfile(),
            'services' => $services,
        ]);
    }

    public function show(Service $service): View
    {
        abort_unless($service->isPublished, 404);

        $solutions = ServiceSolution::query()
            ->where('serviceId', $service->serviceId)
            ->where('isPublished', true)
            ->orderBy('displayOrder')
            ->get();

        $benefits = ServiceBenefit::query()
            ->where('serviceId', $service->serviceId)
            ->where('isPublished', true)
            ->orderBy('displayOrder')
            ->get();

        $faqLinks = ServiceFaq::query()
            ->where('serviceId', $service->serviceId)
            ->orderBy('displayOrder')
            ->get();

        $faqMap = Faq::query()
            ->whereIn('faqId', $faqLinks->pluck('faqId'))
            ->where('isPublished', true)
            ->get()
            ->keyBy('faqId');

        $mediaLinks = ServiceMedia::query()
            ->where('serviceId', $service->serviceId)
            ->orderBy('displayOrder')
            ->get();

        $mediaMap = MediaAsset::query()
            ->whereIn('mediaId', $mediaLinks->pluck('mediaId'))
            ->where('isPublic', true)
            ->where('isPublished', true)
            ->get()
            ->keyBy('mediaId');

        $featuredImage = $service->featuredImageId
            ? MediaAsset::query()
                ->where('mediaId', $service->featuredImageId)
                ->where('isPublic', true)
                ->where('isPublished', true)
                ->first()
            : null;

        return view('public.services.show', [
            'companyProfile' => $this->companyProfile(),
            'service' => $service,
            'solutions' => $solutions,
            'benefits' => $benefits,
            'faqLinks' => $faqLinks,
            'faqMap' => $faqMap,
            'mediaLinks' => $mediaLinks,
            'mediaMap' => $mediaMap,
            'featuredImage' => $featuredImage,
        ]);
    }

    private function companyProfile(): ?CompanyProfile
    {
        return CompanyProfile::query()
            ->where('code', 'Main')
            ->first();
    }
}
