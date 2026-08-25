@extends('public.layouts.app')

@section('title', 'Contacto | Somos Constructivos')
@section('metaDescription', 'Ponte en contacto con Somos Constructivos para conversar sobre tu proyecto.')

@section('content')
@php
    $phoneNumber = $companyProfile?->phoneNumber;
    $whatsAppNumber = $companyProfile?->whatsAppNumber;
    $contactEmail = $companyProfile?->contactEmail;
    $businessHours = $companyProfile?->businessHours;

    $addressParts = collect([
        data_get($companyProfile, 'addressLine1'),
        data_get($companyProfile, 'addressLine2'),
        data_get($companyProfile, 'locationNeighborhood'),
        data_get($companyProfile, 'locationCity'),
        data_get($companyProfile, 'locationState'),
        data_get($companyProfile, 'postalCode'),
    ])->filter();

    $addressText = $addressParts->join(', ');

    $cleanWhatsApp = $whatsAppNumber
        ? preg_replace('/\D+/', '', $whatsAppNumber)
        : null;

    $cleanPhone = $phoneNumber
        ? preg_replace('/[^\d+]/', '', $phoneNumber)
        : null;
@endphp

<section class="contact-hero">
    <div class="site-shell">
        <span class="eyebrow">CONTACTO</span>

        <h1>
            Hablemos de
            tu próximo proyecto.
        </h1>

        <p>
            Cuéntanos qué necesitas. Podemos orientarte
            sobre el servicio adecuado y el siguiente paso
            para comenzar.
        </p>
    </div>
</section>

<section class="public-section">
    <div class="site-shell contact-layout">
        <div class="contact-intro">
            <span class="eyebrow">SOMOS CONSTRUCTIVOS</span>

            <h2>
                Estamos para ayudarte
                a convertir una idea
                en una solución.
            </h2>

            <p>
                Para una cotización completa, utiliza
                nuestro formulario y adjunta fotografías,
                planos, croquis o referencias.
            </p>

            <a
                class="public-button"
                href="{{ route('public.quote.create') }}"
            >
                Solicitar cotización
            </a>
        </div>

        <div class="contact-card-list">
            @if ($cleanWhatsApp)
                <article class="contact-card">
                    <span class="contact-card__number">01</span>
                    <div>
                        <span class="contact-card__label">WHATSAPP</span>
                        <h3>{{ $whatsAppNumber }}</h3>
                        <a
                            class="public-link"
                            href="https://wa.me/{{ $cleanWhatsApp }}"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Iniciar conversación →
                        </a>
                    </div>
                </article>
            @endif

            @if ($phoneNumber)
                <article class="contact-card">
                    <span class="contact-card__number">02</span>
                    <div>
                        <span class="contact-card__label">TELÉFONO</span>
                        <h3>{{ $phoneNumber }}</h3>
                        @if ($cleanPhone)
                            <a
                                class="public-link"
                                href="tel:{{ $cleanPhone }}"
                            >
                                Llamar →
                            </a>
                        @endif
                    </div>
                </article>
            @endif

            @if ($contactEmail)
                <article class="contact-card">
                    <span class="contact-card__number">03</span>
                    <div>
                        <span class="contact-card__label">CORREO</span>
                        <h3>{{ $contactEmail }}</h3>
                        <a
                            class="public-link"
                            href="mailto:{{ $contactEmail }}"
                        >
                            Escribir correo →
                        </a>
                    </div>
                </article>
            @endif

            @if ($addressText !== '')
                <article class="contact-card">
                    <span class="contact-card__number">04</span>
                    <div>
                        <span class="contact-card__label">UBICACIÓN</span>
                        <h3>{{ $addressText }}</h3>
                    </div>
                </article>
            @endif

            @if ($businessHours)
                <article class="contact-card">
                    <span class="contact-card__number">05</span>
                    <div>
                        <span class="contact-card__label">HORARIO</span>
                        <h3>{{ $businessHours }}</h3>
                    </div>
                </article>
            @endif

            @if (
                !$cleanWhatsApp
                && !$phoneNumber
                && !$contactEmail
                && $addressText === ''
                && !$businessHours
            )
                <div class="contact-empty">
                    <strong>Datos de contacto pendientes.</strong>
                    <p>
                        La página ya funciona. En Configuración
                        agregaremos teléfono, WhatsApp, correo,
                        domicilio y horario.
                    </p>
                </div>
            @endif
        </div>
    </div>
</section>

<section class="contact-options">
    <div class="site-shell">
        <div class="contact-options__heading">
            <span class="eyebrow">¿CÓMO PODEMOS AYUDARTE?</span>
            <h2>
                Elige el camino
                que mejor se adapte
                a lo que necesitas.
            </h2>
        </div>

        <div class="contact-option-grid">
            <article>
                <span>01</span>
                <h3>Quiero cotizar</h3>
                <p>
                    Comparte los datos de tu proyecto
                    y recibe un folio de seguimiento.
                </p>
                <a
                    class="public-link"
                    href="{{ route('public.quote.create') }}"
                >
                    Iniciar cotización →
                </a>
            </article>

            <article>
                <span>02</span>
                <h3>Quiero ver trabajos</h3>
                <p>
                    Revisa proyectos realizados
                    y utiliza uno como referencia.
                </p>
                <a
                    class="public-link"
                    href="{{ route('public.projects.index') }}"
                >
                    Ver proyectos →
                </a>
            </article>

            <article>
                <span>03</span>
                <h3>Quiero conocer servicios</h3>
                <p>
                    Explora nuestras especialidades
                    y soluciones disponibles.
                </p>
                <a
                    class="public-link"
                    href="{{ route('public.services.index') }}"
                >
                    Ver servicios →
                </a>
            </article>
        </div>
    </div>
</section>

<section class="contact-final-cta">
    <div class="site-shell contact-final-cta__inner">
        <div>
            <span class="eyebrow eyebrow--light">
                TU PROYECTO, NUESTRO COMPROMISO!
            </span>

            <h2>
                Empecemos por
                conocer tu espacio.
            </h2>

            <p>
                Una fotografía, medidas aproximadas
                y una breve descripción pueden ser
                suficientes para iniciar la conversación.
            </p>
        </div>

        <a
            class="public-button public-button--light"
            href="{{ route('public.quote.create') }}"
        >
            Cuéntanos tu proyecto
        </a>
    </div>
</section>
@endsection
