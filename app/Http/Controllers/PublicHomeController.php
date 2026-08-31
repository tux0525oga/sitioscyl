<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\MediaAsset;
use App\Models\Project;
use App\Models\Service;
use Illuminate\View\View;

class PublicHomeController extends Controller
{
    public function index(): View
    {
        $services = Service::query()
            ->with('featuredImage')
            ->where('isPublished', true)
            ->orderByDesc('isFeatured')
            ->orderBy('displayOrder')
            ->orderBy('name')
            ->limit(6)
            ->get();

        $projects = Project::query()
            ->with([
                'featuredImage',
                'serviceLinks.service',
            ])
            ->where('isPublished', true)
            ->orderByDesc('isFeatured')
            ->orderBy('displayOrder')
            ->orderByDesc('projectYear')
            ->orderByDesc('createdAt')
            ->limit(4)
            ->get();

        $companyProfile = $this->companyProfile();

        $heroMediaAsset = $this->publishedMedia(
            $companyProfile?->homeHeroMedia
        );

        $heroProject = null;

        if ($heroMediaAsset === null) {
            $heroProject = $projects->first(
                fn ($project) =>
                    $project->featuredImage !== null
                    && $project->featuredImage->isPublic
                    && $project->featuredImage->isPublished
            );

            $heroMediaAsset = $heroProject?->featuredImage;
        }

        return view('public.home.index', [
            'companyProfile' => $companyProfile,
            'services' => $services,
            'projects' => $projects,
            'heroMediaAsset' => $heroMediaAsset,
            'heroProject' => $heroProject,
        ]);
    }

    private function companyProfile(): ?CompanyProfile
    {
        return CompanyProfile::query()
            ->with('homeHeroMedia')
            ->where('code', 'Main')
            ->first();
    }

    private function publishedMedia(
        ?MediaAsset $mediaAsset
    ): ?MediaAsset {
        if (
            $mediaAsset === null
            || !$mediaAsset->isPublic
            || !$mediaAsset->isPublished
        ) {
            return null;
        }

        return $mediaAsset;
    }
}
