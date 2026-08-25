<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use App\Models\Project;
use App\Models\ProjectComparison;
use App\Models\ProjectMedia;
use Illuminate\View\View;

class PublicProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::query()
            ->with(['featuredImage', 'serviceLinks.service'])
            ->where('isPublished', true)
            ->orderByDesc('isFeatured')
            ->orderBy('displayOrder')
            ->orderByDesc('projectYear')
            ->orderByDesc('createdAt')
            ->get();

        return view('public.projects.index', [
            'companyProfile' => $this->companyProfile(),
            'projects' => $projects,
        ]);
    }

    public function show(Project $project): View
    {
        abort_unless($project->isPublished, 404);

        $project->load([
            'featuredImage',
            'serviceLinks.service',
            'tagLinks.tag',
        ]);

        $mediaItems = ProjectMedia::query()
            ->with(['mediaAsset', 'mediaCategory'])
            ->where('projectId', $project->projectId)
            ->orderBy('displayOrder')
            ->orderBy('createdAt')
            ->get()
            ->filter(fn ($item) =>
                $item->mediaAsset !== null
                && $item->mediaAsset->isPublic
                && $item->mediaAsset->isPublished
            )
            ->values();

        $comparisons = ProjectComparison::query()
            ->with(['beforeImage', 'afterImage'])
            ->where('projectId', $project->projectId)
            ->where('isPublished', true)
            ->orderBy('displayOrder')
            ->get()
            ->filter(fn ($comparison) =>
                $comparison->beforeImage !== null
                && $comparison->afterImage !== null
                && $comparison->beforeImage->isPublic
                && $comparison->beforeImage->isPublished
                && $comparison->afterImage->isPublic
                && $comparison->afterImage->isPublished
            )
            ->values();

        return view('public.projects.show', [
            'companyProfile' => $this->companyProfile(),
            'project' => $project,
            'mediaItems' => $mediaItems,
            'comparisons' => $comparisons,
        ]);
    }

    private function companyProfile(): ?CompanyProfile
    {
        return CompanyProfile::query()
            ->where('code', 'Main')
            ->first();
    }
}
