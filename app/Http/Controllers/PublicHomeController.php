<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
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

        $heroProject = $projects->first(
            fn ($project) =>
                $project->featuredImage !== null
                && $project->featuredImage->isPublic
                && $project->featuredImage->isPublished
        );

        return view('public.home.index', [
            'companyProfile' => $this->companyProfile(),
            'services' => $services,
            'projects' => $projects,
            'heroProject' => $heroProject,
        ]);
    }

    private function companyProfile(): ?CompanyProfile
    {
        return CompanyProfile::query()
            ->where('code', 'Main')
            ->first();
    }
}
