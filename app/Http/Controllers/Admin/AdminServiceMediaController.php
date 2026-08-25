<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceMedia;
use App\Models\UserAccount;
use App\Services\ServiceMediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminServiceMediaController extends Controller
{
    public function store(
        Request $request,
        Service $service,
        ServiceMediaService $serviceMediaService
    ): RedirectResponse {
        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:15360',
            ],
            'mediaCategoryId' => [
                'nullable',
                'string',
                Rule::exists(
                    'mediacategory',
                    'mediaCategoryId'
                ),
            ],
            'title' => [
                'nullable',
                'string',
                'max:255',
            ],
            'altText' => [
                'nullable',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'displayOrder' => [
                'nullable',
                'integer',
                'min:0',
                'max:65535',
            ],
            'isFeatured' => [
                'nullable',
                'boolean',
            ],
        ]);

        $user = $request->user();

        $serviceMediaService->uploadImage(
            $service,
            $request->file('file'),
            $validated,
            $user instanceof UserAccount
                ? $user
                : null
        );

        return redirect()
            ->route(
                'admin.services.edit',
                $service
            )
            ->with(
                'success',
                'Imagen agregada al servicio.'
            );
    }

    public function feature(
        Service $service,
        ServiceMedia $serviceMedia,
        ServiceMediaService $serviceMediaService
    ): RedirectResponse {
        $serviceMediaService->setFeatured(
            $service,
            $serviceMedia
        );

        return redirect()
            ->route(
                'admin.services.edit',
                $service
            )
            ->with(
                'success',
                'Imagen principal actualizada.'
            );
    }

    public function destroy(
        Service $service,
        ServiceMedia $serviceMedia,
        ServiceMediaService $serviceMediaService
    ): RedirectResponse {
        $serviceMediaService->remove(
            $service,
            $serviceMedia
        );

        return redirect()
            ->route(
                'admin.services.edit',
                $service
            )
            ->with(
                'success',
                'Imagen retirada del servicio.'
            );
    }
}
