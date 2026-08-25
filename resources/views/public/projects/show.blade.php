@extends('public.layouts.app')

@section('title', $project->name . ' | Somos Constructivos')
@section('metaDescription', $project->shortDescription ?: 'Proyecto realizado por Somos Constructivos.')

@section('content')
@php
    $heroUrl = null;
    if ($project->featuredImage && $project->featuredImage->isPublic && $project->featuredImage->isPublished) {
        $heroUrl = \Illuminate\Support\Facades\Storage::disk(
            $project->featuredImage->storageDisk
        )->url($project->featuredImage->storagePath);
    }
@endphp

<section class="project-detail-hero">
    @if ($heroUrl)
        <div class="project-detail-hero__media"><img src="{{ $heroUrl }}" alt="{{ $project->featuredImage->altText ?: $project->name }}"></div>
    @endif
    <div class="project-detail-hero__overlay"></div>

    <div class="site-shell project-detail-hero__content">
        <a class="back-link back-link--light" href="{{ route('public.projects.index') }}">← Todos los proyectos</a>
        <span class="eyebrow eyebrow--light">PROYECTO</span>
        <h1>{{ $project->name }}</h1>
        @if ($project->shortDescription)<p>{{ $project->shortDescription }}</p>@endif

        <div class="project-detail-meta">
            @if ($project->projectYear)<span>{{ $project->projectYear }}</span>@endif
            @if ($project->locationCity || $project->locationState)
                <span>{{ collect([$project->locationCity, $project->locationState])->filter()->join(', ') }}</span>
            @endif
        </div>
    </div>
</section>

@if ($project->description || $project->challengeDescription || $project->solutionDescription)
<section class="public-section">
    <div class="site-shell project-story">
        <div>
            <span class="eyebrow">EL PROYECTO</span>
            <h2>Una solución desarrollada para el espacio.</h2>

            @if ($project->serviceLinks->isNotEmpty())
                <div class="project-service-list">
                    @foreach ($project->serviceLinks as $serviceLink)<span>{{ $serviceLink->service->name }}</span>@endforeach
                </div>
            @endif

            @if ($project->tagLinks->isNotEmpty())
                <div class="project-tag-list">
                    @foreach ($project->tagLinks as $tagLink)<span>{{ $tagLink->tag->name }}</span>@endforeach
                </div>
            @endif
        </div>

        <div class="project-story__copy">
            @if ($project->description)<div class="story-block"><span class="story-label">DESCRIPCIÓN</span><p>{{ $project->description }}</p></div>@endif
            @if ($project->challengeDescription)<div class="story-block"><span class="story-label">RETO</span><p>{{ $project->challengeDescription }}</p></div>@endif
            @if ($project->solutionDescription)<div class="story-block"><span class="story-label">SOLUCIÓN</span><p>{{ $project->solutionDescription }}</p></div>@endif
        </div>
    </div>
</section>
@endif

@if ($mediaItems->isNotEmpty())
<section class="public-section public-section--dark">
    <div class="site-shell">
        <span class="eyebrow eyebrow--light">GALERÍA</span>
        <h2>Proceso, detalles y resultado.</h2>
        <div class="project-detail-gallery">
            @foreach ($mediaItems as $mediaItem)
                @php
                    $asset = $mediaItem->mediaAsset;
                    $imageUrl = \Illuminate\Support\Facades\Storage::disk($asset->storageDisk)->url($asset->storagePath);
                @endphp
                <figure class="project-detail-gallery__item {{ $loop->first ? 'project-detail-gallery__item--large' : '' }}">
                    <img src="{{ $imageUrl }}" alt="{{ $asset->altText ?: $project->name }}" loading="lazy">
                    <figcaption>
                        @if ($mediaItem->mediaCategory)<span>{{ $mediaItem->mediaCategory->name }}</span>@endif
                        @if ($asset->title)<strong>{{ $asset->title }}</strong>@endif
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif

@if ($comparisons->isNotEmpty())
<section class="public-section">
    <div class="site-shell">
        <span class="eyebrow">ANTES / DESPUÉS</span>
        <h2>La transformación en perspectiva.</h2>
        <div class="public-comparison-list">
            @foreach ($comparisons as $comparison)
                @php
                    $beforeUrl = \Illuminate\Support\Facades\Storage::disk($comparison->beforeImage->storageDisk)->url($comparison->beforeImage->storagePath);
                    $afterUrl = \Illuminate\Support\Facades\Storage::disk($comparison->afterImage->storageDisk)->url($comparison->afterImage->storagePath);
                @endphp
                <article class="public-comparison-card">
                    <div class="public-comparison-card__images">
                        <figure><img src="{{ $beforeUrl }}" alt="Antes de {{ $project->name }}"><figcaption>Antes</figcaption></figure>
                        <figure><img src="{{ $afterUrl }}" alt="Después de {{ $project->name }}"><figcaption>Después</figcaption></figure>
                    </div>
                    @if ($comparison->title || $comparison->description)
                        <div class="public-comparison-card__body">
                            @if ($comparison->title)<h3>{{ $comparison->title }}</h3>@endif
                            @if ($comparison->description)<p>{{ $comparison->description }}</p>@endif
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="project-cta">
    <div class="site-shell project-cta__inner">
        <div>
            <span class="eyebrow eyebrow--light">¿TE GUSTA ESTE RESULTADO?</span>
            <h2>Quiero algo como esto.</h2>
            <p>Podemos tomar este proyecto como referencia para desarrollar una propuesta adaptada a tu espacio.</p>
        </div>
        <a
            class="public-button public-button--light"
                href="{{
                route(
                    'public.quote.create',
                    [
                        'project' => $project->slug,
                    ]
                    )
                }}"
            >
                Solicitar una propuesta
            </a>
    </div>
</section>
@endsection
