<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyProfile;
use App\Models\MediaAsset;
use App\Models\UserAccount;
use App\Services\CompanyIdentityMediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminCompanyProfileController extends Controller
{
    public function edit(): View
    {
        $companyProfile = CompanyProfile::query()
            ->with([
                'logo',
                'monogram',
                'homeHeroMedia',
            ])
            ->where('code', 'Main')
            ->firstOrFail();

        return view('admin.configuration.edit', [
            'companyProfile' => $companyProfile,
            'logoUrl' => $this->mediaUrl(
                $companyProfile->logo
            ),
            'monogramUrl' => $this->mediaUrl(
                $companyProfile->monogram
            ),
            'homeHeroUrl' => $this->mediaUrl(
                $companyProfile->homeHeroMedia
            ),
        ]);
    }

    public function update(
        Request $request,
        CompanyIdentityMediaService $identityMediaService
    ): RedirectResponse {
        $companyProfile = CompanyProfile::query()
            ->where('code', 'Main')
            ->firstOrFail();

        $validated = $request->validate([
            'companyName' => [
                'required',
                'string',
                'max:190',
            ],
            'slogan' => [
                'nullable',
                'string',
                'max:255',
            ],
            'phoneNumber' => [
                'nullable',
                'string',
                'max:40',
            ],
            'whatsAppNumber' => [
                'nullable',
                'string',
                'max:40',
            ],
            'contactEmail' => [
                'nullable',
                'email',
                'max:190',
            ],
            'addressLine' => [
                'nullable',
                'string',
                'max:255',
            ],
            'locationCity' => [
                'nullable',
                'string',
                'max:120',
            ],
            'locationState' => [
                'nullable',
                'string',
                'max:120',
            ],
            'postalCode' => [
                'nullable',
                'string',
                'max:20',
            ],
            'businessHours' => [
                'nullable',
                'string',
                'max:500',
            ],
            'logoFile' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:15360',
            ],
            'monogramFile' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:15360',
            ],
            'homeHeroFile' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:15360',
            ],
        ]);

        $companyProfile->fill([
            'companyName' => $validated['companyName'],
            'slogan' => $validated['slogan'] ?? null,
            'phoneNumber' => $validated['phoneNumber'] ?? null,
            'whatsAppNumber' => $validated['whatsAppNumber'] ?? null,
            'contactEmail' => $validated['contactEmail'] ?? null,
            'addressLine' => $validated['addressLine'] ?? null,
            'locationCity' => $validated['locationCity'] ?? null,
            'locationState' => $validated['locationState'] ?? null,
            'postalCode' => $validated['postalCode'] ?? null,
            'businessHours' => $validated['businessHours'] ?? null,
        ]);

        $companyProfile->save();

        $user = $request->user();

        if ($request->hasFile('logoFile')) {
            $identityMediaService->uploadLogo(
                $companyProfile,
                $request->file('logoFile'),
                $user instanceof UserAccount
                    ? $user
                    : null
            );
        }

        if ($request->hasFile('monogramFile')) {
            $identityMediaService->uploadMonogram(
                $companyProfile,
                $request->file('monogramFile'),
                $user instanceof UserAccount
                    ? $user
                    : null
            );
        }

        if ($request->hasFile('homeHeroFile')) {
            $identityMediaService->uploadHomeHero(
                $companyProfile,
                $request->file('homeHeroFile'),
                $user instanceof UserAccount
                    ? $user
                    : null
            );
        }

        return redirect()
            ->route('admin.configuration.edit')
            ->with(
                'success',
                'Configuración actualizada correctamente.'
            );
    }

    private function mediaUrl(
        ?MediaAsset $mediaAsset
    ): ?string {
        if ($mediaAsset === null) {
            return null;
        }

        return Storage::disk(
            $mediaAsset->storageDisk
        )->url(
            $mediaAsset->storagePath
        );
    }
}
