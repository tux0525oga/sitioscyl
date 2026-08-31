@extends('public.layouts.app')

@php
    $seoTitle = $seo?->metaTitle
        ?: (($service->heroTitle ?: $service->name) . ' | Somos Constructivos');

    $seoDescription = $seo?->metaDescription
        ?: ($service->shortDescription ?: 'Servicio de Somos Constructivos.');

    $canonicalUrl = $seo?->canonicalUrl
        ?: url()->current();

    $robotsIndex = $seo?->robotsIndex ?? true;
    $robotsFollow = $seo?->robotsFollow ?? true;

    $robots = ($robotsIndex ? 'index' : 'noindex')
        . ','
        . ($robotsFollow ? 'follow' : 'nofollow');

    $socialTitle = $seo?->socialTitle
        ?: $seoTitle;

    $socialDescription = $seo?->socialDescription
        ?: $seoDescription;

    $socialImageAsset = $socialImage
        ?: $featuredImage;

    $socialImageUrl = $socialImageAsset
        ? \Illuminate\Support\Facades\Storage::disk(
            $socialImageAsset->storageDisk
        )->url($socialImageAsset->storagePath)
        : null;

    $heroUrl = $featuredImage
        ? \Illuminate\Support\Facades\Storage::disk(
            $featuredImage->storageDisk
        )->url($featuredImage->storagePath)
        : null;
@endphp

@section('title', $seoTitle)
@section('metaDescription', $seoDescription)
@section('canonicalUrl', $canonicalUrl)
@section('robots', $robots)
@section('ogTitle', $socialTitle)
@section('ogDescription', $socialDescription)
@section('ogUrl', $canonicalUrl)
@section('ogType', 'website')

@if ($socialImageUrl)
    @section('ogImage', $socialImageUrl)
    @section('twitterCard', 'summary_large_image')
@endif

@section('content')
<section class="service-hero" @if($heroUrl) style="background-image: linear-gradient(rgba(20,33,43,.78), rgba(20,33,43,.78)), url('{{ $heroUrl }}')" @endif>
    <div class="site-shell">
        <a class="back-link" href="{{ route('public.services.index') }}">← Todos los servicios</a>
        <span class="eyebrow eyebrow--light">SERVICIO</span>
        <h1>{{ $service->heroTitle ?: $service->name }}</h1>
        <p>{{ $service->heroSubtitle ?: $service->shortDescription }}</p>
        <a
            class="public-button"
            href="{{
            route(
                'public.quote.create',
                    [
                        'service' => $service->slug,
                    ]
                )
            }}"
        >
        Cotizar este servicio
        </a>
    </div>
</section>
@if ($service->description)
<section class="public-section">
    <div class="site-shell split">
        <div><span class="eyebrow">SOBRE EL SERVICIO</span><h2>{{ $service->name }}</h2></div>
        <p class="rich-text">{{ $service->description }}</p>
    </div>
</section>
@endif
@if ($solutions->isNotEmpty())
<section class="public-section public-section--soft">
    <div class="site-shell">
        <span class="eyebrow">SOLUCIONES</span><h2>Opciones dentro de {{ $service->name }}</h2>
        <div class="content-grid">
            @foreach ($solutions as $solution)
                <article class="content-card"><h3>{{ $solution->name }}</h3><p>{{ $solution->shortDescription ?: $solution->description }}</p></article>
            @endforeach
        </div>
    </div>
</section>
@endif
@if ($benefits->isNotEmpty())
<section class="public-section">
    <div class="site-shell">
        <span class="eyebrow">BENEFICIOS</span><h2>Por qué elegir esta solución</h2>
        <div class="content-grid content-grid--4">
            @foreach ($benefits as $benefit)
                <article class="content-card"><h3>{{ $benefit->title }}</h3><p>{{ $benefit->description }}</p></article>
            @endforeach
        </div>
    </div>
</section>
@endif
@if ($mediaMap->isNotEmpty())
<section class="public-section public-section--dark">
    <div class="site-shell">
        <span class="eyebrow eyebrow--light">GALERÍA</span><h2>Detalles y resultados</h2>
                <div class="gallery-grid">
            @foreach ($mediaLinks as $mediaLink)
                @php
                    $asset = $mediaMap->get($mediaLink->mediaId);
                @endphp

                @if ($asset)
                    @php
                        $url = \Illuminate\Support\Facades\Storage::disk(
                            $asset->storageDisk
                        )->url($asset->storagePath);

                        $caption = $asset->title
                            ?: $asset->altText
                            ?: $service->name;
                    @endphp

                    <figure>
                        <button
                            class="public-gallery-trigger"
                            type="button"
                            data-gallery-src="{{ $url }}"
                            data-gallery-alt="{{ $asset->altText ?: $service->name }}"
                            data-gallery-caption="{{ $caption }}"
                            aria-label="Ampliar imagen: {{ $caption }}"
                        >
                            <img
                                src="{{ $url }}"
                                alt="{{ $asset->altText ?: $service->name }}"
                                loading="lazy"
                            >

                            <span
                                class="public-gallery-trigger__hint"
                                aria-hidden="true"
                            >
                                Ver imagen
                            </span>
                        </button>

                        @if ($asset->title)
                            <figcaption>
                                {{ $asset->title }}
                            </figcaption>
                        @endif
                    </figure>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif
@php $visibleFaqLinks = $faqLinks->filter(fn ($link) => $faqMap->has($link->faqId)); @endphp
@if ($visibleFaqLinks->isNotEmpty())
<section class="public-section">
    <div class="site-shell split">
        <div><span class="eyebrow">PREGUNTAS FRECUENTES</span><h2>Antes de comenzar tu proyecto</h2></div>
        <div>
            @foreach ($visibleFaqLinks as $faqLink)
                @php $faq = $faqMap->get($faqLink->faqId); @endphp
                <details class="faq-item"><summary>{{ $faq->question }}</summary><p>{{ $faq->answer }}</p></details>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
