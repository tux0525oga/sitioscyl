@extends('public.layouts.app')

@section('title', 'Proyectos | Somos Constructivos')
@section('metaDescription', 'Conoce proyectos realizados por Somos Constructivos.')

@section('content')
<section class="project-index-hero">
    <div class="site-shell">
        <span class="eyebrow">PROYECTOS</span>
        <h1>Espacios transformados con soluciones a la medida.</h1>
        <p>Una selección de trabajos que muestran materiales, procesos y resultados desarrollados para cada proyecto.</p>
    </div>
</section>

<section class="public-section">
    <div class="site-shell">
        @if ($projects->isEmpty())
            <div class="public-empty">Próximamente publicaremos nuestros proyectos.</div>
        @else
            <div class="project-public-grid">
                @foreach ($projects as $project)
                    @php
                        $imageUrl = null;
                        if ($project->featuredImage && $project->featuredImage->isPublic && $project->featuredImage->isPublished) {
                            $imageUrl = \Illuminate\Support\Facades\Storage::disk(
                                $project->featuredImage->storageDisk
                            )->url($project->featuredImage->storagePath);
                        }
                    @endphp

                    <article class="project-public-card {{ $loop->first ? 'project-public-card--featured' : '' }}">
                        <a class="project-public-card__media" href="{{ route('public.projects.show', $project->slug) }}">
                            @if ($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $project->featuredImage->altText ?: $project->name }}" loading="lazy">
                            @else
                                <div class="project-public-card__placeholder">SC</div>
                            @endif

                            @if ($project->isFeatured)
                                <span class="project-public-card__featured">DESTACADO</span>
                            @endif
                        </a>

                        <div class="project-public-card__body">
                            <div class="project-public-card__meta">
                                @if ($project->projectYear)<span>{{ $project->projectYear }}</span>@endif
                                @if ($project->locationCity || $project->locationState)
                                    <span>{{ collect([$project->locationCity, $project->locationState])->filter()->join(', ') }}</span>
                                @endif
                            </div>

                            <h2><a href="{{ route('public.projects.show', $project->slug) }}">{{ $project->name }}</a></h2>
                            @if ($project->shortDescription)<p>{{ $project->shortDescription }}</p>@endif

                            @if ($project->serviceLinks->isNotEmpty())
                                <div class="project-service-list">
                                    @foreach ($project->serviceLinks as $serviceLink)
                                        <span>{{ $serviceLink->service->name }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <a class="public-link" href="{{ route('public.projects.show', $project->slug) }}">Ver proyecto →</a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
