<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\MediaAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminMediaController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim(
            (string) $request->query('search', '')
        );

        $usage = (string) $request->query(
            'usage',
            ''
        );

        $companyProfile = CompanyProfile::query()
            ->where('code', 'Main')
            ->first();

        $identityMediaIds = collect([
            $companyProfile?->logoMediaId,
            $companyProfile?->monogramMediaId,
        ])
            ->filter()
            ->values();

        $mediaAssets = MediaAsset::query()
            ->withCount([
                'projectLinks',
                'serviceLinks',
            ])
            ->when(
                $search !== '',
                function ($query) use ($search): void {
                    $query->where(
                        function ($query) use ($search): void {
                            $query
                                ->where(
                                    'title',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'originalFileName',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'fileName',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
                }
            )
            ->when(
                $usage === 'project',
                fn ($query) =>
                    $query->whereHas('projectLinks')
            )
            ->when(
                $usage === 'service',
                fn ($query) =>
                    $query->whereHas('serviceLinks')
            )
            ->when(
                $usage === 'identity',
                function ($query) use (
                    $identityMediaIds
                ): void {
                    if ($identityMediaIds->isEmpty()) {
                        $query->whereRaw('1 = 0');

                        return;
                    }

                    $query->whereIn(
                        'mediaId',
                        $identityMediaIds->all()
                    );
                }
            )
            ->when(
                $usage === 'unlinked',
                function ($query) use (
                    $identityMediaIds
                ): void {
                    $query
                        ->whereDoesntHave('projectLinks')
                        ->whereDoesntHave('serviceLinks');

                    if ($identityMediaIds->isNotEmpty()) {
                        $query->whereNotIn(
                            'mediaId',
                            $identityMediaIds->all()
                        );
                    }
                }
            )
            ->orderByDesc('createdAt')
            ->paginate(24)
            ->withQueryString();

        $mediaUrls = $mediaAssets
            ->getCollection()
            ->mapWithKeys(
                fn (MediaAsset $mediaAsset): array => [
                    $mediaAsset->mediaId =>
                        Storage::disk(
                            $mediaAsset->storageDisk
                        )->url(
                            $mediaAsset->storagePath
                        ),
                ]
            )
            ->all();

        return view('admin.media.index', [
            'mediaAssets' => $mediaAssets,
            'mediaUrls' => $mediaUrls,
            'companyProfile' => $companyProfile,
            'search' => $search,
            'usage' => $usage,
        ]);
    }
}