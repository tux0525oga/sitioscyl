@extends('public.layouts.app')

@section(
    'title',
    'Solicitud recibida | Somos Constructivos'
)

@section(
    'metaDescription',
    'Tu solicitud de cotización fue recibida por Somos Constructivos.'
)

@section('content')
<section class="quote-thanks">
    <div class="site-shell quote-thanks__inner">
        <span class="quote-thanks__check">
            ✓
        </span>

        <span class="eyebrow">
            SOLICITUD RECIBIDA
        </span>

        <h1>
            Gracias por compartir
            tu proyecto.
        </h1>

        <p>
            Registramos correctamente tu solicitud.
            Conserva este folio para cualquier
            seguimiento:
        </p>

        <div class="quote-folio">
            {{ $folio }}
        </div>

        <p class="quote-thanks__secondary">
            Nuestro equipo revisará la información
            y los archivos enviados antes de
            contactarte.
        </p>

        @if (session('fileUploadWarning'))
            <div class="quote-thanks__warning">
                {{ session('fileUploadWarning') }}
            </div>
        @endif

        <div class="quote-thanks__actions">
            <a
                class="public-button"
                href="/"
            >
                Ir al inicio
            </a>

            <a
                class="quote-secondary-button quote-secondary-button--link"
                href="{{ route('public.projects.index') }}"
            >
                Ver proyectos
            </a>
        </div>
    </div>
</section>
@endsection
