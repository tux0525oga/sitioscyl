@extends('public.layouts.app')

@section('title', 'Servicios | Somos Constructivos')
@section('metaDescription', 'Conoce los servicios constructivos de Somos Constructivos.')

@section('content')
<section class="public-hero">
    <div class="site-shell">
        <span class="eyebrow">NUESTROS SERVICIOS</span>
        <h1>Soluciones constructivas para transformar tus espacios.</h1>
        <p>Diseño, suministro, fabricación e instalación con una visión integral del proyecto.</p>
    </div>
</section>
<section class="public-section">
    <div class="site-shell">
        @if ($services->isEmpty())
            <div class="public-empty">Próximamente publicaremos nuestros servicios.</div>
        @else
            <div class="service-grid">
                @foreach ($services as $service)
                    @php
                        $imageUrl = $service->featuredImage
                            ? \Illuminate\Support\Facades\Storage::disk($service->featuredImage->storageDisk)->url($service->featuredImage->storagePath)
                            : null;
                    @endphp
                    <article class="service-card">
                        <a class="service-card__image" href="{{ route('public.services.show', $service->slug) }}">
                            @if ($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $service->featuredImage->altText ?: $service->name }}">
                            @else
                                <span>SC</span>
                            @endif
                        </a>
                        <div class="service-card__body">
                            <span class="eyebrow">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <h2>{{ $service->name }}</h2>
                            <p>{{ $service->shortDescription ?: 'Conoce las soluciones disponibles para este servicio.' }}</p>
                            <a class="public-link" href="{{ route('public.services.show', $service->slug) }}">Ver servicio →</a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
