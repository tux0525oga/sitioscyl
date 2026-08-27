<?php

namespace App\Providers;

use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer(
            'public.layouts.app',
            function ($view): void {
                $companyProfile = CompanyProfile::query()
                    ->with([
                        'logo',
                        'monogram',
                    ])
                    ->where('code', 'Main')
                    ->first();

                $companyMonogramUrl =
                    asset('images/brand/sc-monograma.png');

                $companyLogoUrl = null;

                if ($companyProfile?->monogram) {
                    $companyMonogramUrl = Storage::disk(
                        $companyProfile->monogram->storageDisk
                    )->url(
                        $companyProfile->monogram->storagePath
                    );
                }

                if ($companyProfile?->logo) {
                    $companyLogoUrl = Storage::disk(
                        $companyProfile->logo->storageDisk
                    )->url(
                        $companyProfile->logo->storagePath
                    );
                }

                $view->with([
                    'companyProfile' =>
                        $companyProfile,

                    'companyMonogramUrl' =>
                        $companyMonogramUrl,

                    'companyLogoUrl' =>
                        $companyLogoUrl,
                ]);
            }
        );
    }
}