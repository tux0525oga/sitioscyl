@extends('public.layouts.app')

@section('title', 'Somos Constructivos | Tu proyecto, nuestro compromiso!')
@section('metaDescription', 'Soluciones de vidrio, aluminio, PVC, herrería, remodelación, acabados y servicios constructivos.')

@section('content')
@php
    $heroUrl = null;
    $heroAlt = 'Somos Constructivos';

    if ($heroMediaAsset) {
        $heroUrl = \Illuminate\Support\Facades\Storage::disk(
            $heroMediaAsset->storageDisk
        )->url(
            $heroMediaAsset->storagePath
        );

        $heroAlt = $heroMediaAsset->altText
            ?: 'Somos Constructivos';
    }
@endphp

<section class="home-hero">
    @if ($heroUrl)
        <div class="home-hero__media">
            <img
                src="{{ $heroUrl }}"
                alt="{{ $heroAlt }}"
            >
        </div>
    @endif

    <div class="home-hero__overlay"></div>

    <div class="site-shell home-hero__content">
        <span class="eyebrow eyebrow--light">SOMOS CONSTRUCTIVOS</span>

        <h1>
            Construimos soluciones
            para espacios que importan.
        </h1>

        <p>
            Integramos diseño, fabricación, instalación
            y servicios constructivos para proyectos
            residenciales con atención en cada detalle.
        </p>

        <div class="home-hero__actions">
            <a
                class="public-button public-button--accent"
                href="{{ route('public.quote.create') }}"
            >
                Cotizar mi proyecto
            </a>

            <a
                class="home-secondary-link"
                href="{{ route('public.projects.index') }}"
            >
                Ver proyectos →
            </a>
        </div>
    </div>
</section>

<section class="public-section">
    <div class="site-shell home-editorial-grid">
        <div>
            <span class="eyebrow">QUIÉNES SOMOS</span>
            <h2>
                Un solo equipo para coordinar
                distintas especialidades.
            </h2>
        </div>

        <div class="home-intro__copy">
            <p>
                En Somos Constructivos reunimos soluciones
                de aluminio, vidrio, PVC, herrería,
                remodelación, acabados y servicios
                complementarios para facilitar la ejecución
                de cada proyecto.
            </p>

            <p>
                Nuestro enfoque busca resolver necesidades
                reales del espacio, cuidar la presentación
                final y mantener una comunicación clara
                durante el proceso.
            </p>
        </div>
    </div>
</section>

<section class="public-section public-section--soft">
    <div class="site-shell">
        <div class="home-section-heading">
            <div>
                <span class="eyebrow">SERVICIOS</span>
                <h2>Soluciones que pueden trabajar juntas.</h2>
            </div>

            <a
                class="public-link"
                href="{{ route('public.services.index') }}"
            >
                Ver todos los servicios →
            </a>
        </div>

        @if ($services->isEmpty())
            <div class="public-empty">
                Próximamente publicaremos nuestros servicios.
            </div>
        @else
            <div class="home-service-grid">
                @foreach ($services as $service)
                    @php
                        $serviceImageUrl = null;

                        if (
                            $service->featuredImage
                            && $service->featuredImage->isPublic
                            && $service->featuredImage->isPublished
                        ) {
                            $serviceImageUrl =
                                \Illuminate\Support\Facades\Storage::disk(
                                    $service->featuredImage->storageDisk
                                )->url(
                                    $service->featuredImage->storagePath
                                );
                        }
                    @endphp

                    <article class="home-service-card">
                        <a
                            class="home-service-card__media"
                            href="{{ route('public.services.show', $service->slug) }}"
                        >
                            @if ($serviceImageUrl)
                                <img
                                    src="{{ $serviceImageUrl }}"
                                    alt="{{ $service->featuredImage->altText ?: $service->name }}"
                                    loading="lazy"
                                >
                            @else
                                <span>SC</span>
                            @endif
                        </a>

                        <div class="home-service-card__body">
                            <span class="eyebrow">
                                {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>

                            <h3>
                                <a href="{{ route('public.services.show', $service->slug) }}">
                                    {{ $service->name }}
                                </a>
                            </h3>

                            <p>
                                {{ $service->shortDescription ?: 'Conoce las soluciones disponibles para este servicio.' }}
                            </p>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>

<section class="public-section">
    <div class="site-shell">
        <div class="home-section-heading">
            <div>
                <span class="eyebrow">NUESTRO PROCESO</span>
                <h2>Del primer contacto a la entrega.</h2>
            </div>
        </div>

        <div class="home-process-grid">
            <article>
                <span>01</span>
                <h3>Cuéntanos tu idea</h3>
                <p>Comparte necesidades, medidas, fotografías o referencias.</p>
            </article>

            <article>
                <span>02</span>
                <h3>Revisamos el espacio</h3>
                <p>Analizamos condiciones, alcances y alternativas.</p>
            </article>

            <article>
                <span>03</span>
                <h3>Preparamos la propuesta</h3>
                <p>Integramos materiales, especificaciones y alcance.</p>
            </article>

            <article>
                <span>04</span>
                <h3>Ejecutamos</h3>
                <p>Coordinamos fabricación, instalación y seguimiento.</p>
            </article>
        </div>
    </div>
</section>

<section class="public-section public-section--dark">
    <div class="site-shell">
        <div class="home-section-heading home-section-heading--dark">
            <div>
                <span class="eyebrow eyebrow--light">PROYECTOS</span>
                <h2>Resultados que cuentan mejor lo que hacemos.</h2>
            </div>

            <a
                class="home-secondary-link"
                href="{{ route('public.projects.index') }}"
            >
                Ver portafolio →
            </a>
        </div>

        @if ($projects->isEmpty())
            <div class="public-empty">
                Próximamente publicaremos nuestros proyectos.
            </div>
        @else
            <div class="home-project-grid">
                @foreach ($projects as $project)
                    @php
                        $projectImageUrl = null;

                        if (
                            $project->featuredImage
                            && $project->featuredImage->isPublic
                            && $project->featuredImage->isPublished
                        ) {
                            $projectImageUrl =
                                \Illuminate\Support\Facades\Storage::disk(
                                    $project->featuredImage->storageDisk
                                )->url(
                                    $project->featuredImage->storagePath
                                );
                        }
                    @endphp

                    <article class="home-project-card {{ $loop->first ? 'home-project-card--large' : '' }}">
                        <a
                            class="home-project-card__media"
                            href="{{ route('public.projects.show', $project->slug) }}"
                        >
                            @if ($projectImageUrl)
                                <img
                                    src="{{ $projectImageUrl }}"
                                    alt="{{ $project->featuredImage->altText ?: $project->name }}"
                                    loading="lazy"
                                >
                            @else
                                <span>SC</span>
                            @endif
                        </a>

                        <div class="home-project-card__body">
                            <h3>
                                <a href="{{ route('public.projects.show', $project->slug) }}">
                                    {{ $project->name }}
                                </a>
                            </h3>

                            @if ($project->shortDescription)
                                <p>{{ $project->shortDescription }}</p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>

<section class="home-final-cta">
    <div class="site-shell home-final-cta__inner">
        <div>
            <span class="eyebrow eyebrow--light">
                TU PROYECTO, NUESTRO COMPROMISO!
            </span>

            <h2>
                ¿Tienes una idea
                que quieres llevar a obra?
            </h2>

            <p>
                Comparte la información principal
                y generaremos un folio para darle
                seguimiento desde el primer contacto.
            </p>
        </div>

        <a
            class="public-button public-button--light"
            href="{{ route('public.quote.create') }}"
        >
            Solicitar cotización
        </a>
    </div>
</section>
@endsection
