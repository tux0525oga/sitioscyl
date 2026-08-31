@extends('public.layouts.app')

@section(
    'title',
    'Cotizar | Somos Constructivos'
)

@section(
    'metaDescription',
    'Solicita una cotización para tu proyecto con Somos Constructivos.'
)

@section('content')
<section class="quote-public-hero">
    <div class="site-shell">
        <span class="eyebrow">
            COTIZAR
        </span>

        <h1>
            Cuéntanos sobre
            tu proyecto.
        </h1>

        <p>
            Comparte la información principal,
            fotografías o referencias.
            Al enviar tu solicitud recibirás
            un folio de seguimiento.
        </p>
    </div>
</section>

<section class="public-section quote-public-section">
    <div class="site-shell quote-form-shell">
        @if ($errors->any())
            <div class="quote-alert quote-alert--error">
                <strong>
                    Revisa la información antes de continuar.
                </strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div
            class="quote-stepper"
            aria-label="Pasos de cotización"
        >
            <div
                class="quote-stepper__item is-active"
                data-step-indicator="1"
            >
                <span>1</span>
                <strong>Servicio</strong>
            </div>

            <div
                class="quote-stepper__item"
                data-step-indicator="2"
            >
                <span>2</span>
                <strong>Proyecto</strong>
            </div>

            <div
                class="quote-stepper__item"
                data-step-indicator="3"
            >
                <span>3</span>
                <strong>Contacto</strong>
            </div>

            <div
                class="quote-stepper__item"
                data-step-indicator="4"
            >
                <span>4</span>
                <strong>Archivos</strong>
            </div>
        </div>

        <form
            id="publicQuoteForm"
            class="quote-public-form"
            method="POST"
            enctype="multipart/form-data"
            action="{{ route('public.quote.store') }}"
        >
            @csrf

            <input
                class="quote-honeypot"
                type="text"
                name="website"
                tabindex="-1"
                autocomplete="off"
                aria-hidden="true"
            >

            <input
                type="hidden"
                name="sourceUrl"
                value="{{ old('sourceUrl', $sourceUrl) }}"
            >

            @php
                $checkedServiceIds = old(
                    'serviceIds',
                    $selectedServiceIds
                );

                $currentProjectId = old(
                    'referenceProjectId',
                    $selectedProjectId
                );
            @endphp

            <section
                class="quote-step is-active"
                data-quote-step="1"
            >
                <div class="quote-step__heading">
                    <span class="eyebrow">
                        PASO 01
                    </span>

                    <h2>
                        ¿Qué servicio necesitas?
                    </h2>

                    <p>
                        Puedes seleccionar una
                        o varias especialidades.
                    </p>
                </div>

                @if ($services->isEmpty())
                    <div class="public-empty">
                        Para solicitar una cotización
                        debe existir al menos un
                        servicio publicado.
                    </div>
                @else
                    <div class="quote-service-grid">
                        @foreach ($services as $service)
                            <label class="quote-choice-card">
                                <input
                                    type="checkbox"
                                    name="serviceIds[]"
                                    value="{{ $service->serviceId }}"
                                    @checked(
                                        in_array(
                                            $service->serviceId,
                                            $checkedServiceIds,
                                            true
                                        )
                                    )
                                >

                                <span
                                    class="quote-choice-card__content"
                                >
                                    <strong>
                                        {{ $service->name }}
                                    </strong>

                                    @if ($service->shortDescription)
                                        <small>
                                            {{
                                                $service
                                                    ->shortDescription
                                            }}
                                        </small>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                @endif

                <div
                    class="quote-field quote-field--spaced"
                >
                    <label for="referenceProjectId">
                        Proyecto de referencia
                        <small>Opcional</small>
                    </label>

                    <select
                        id="referenceProjectId"
                        name="referenceProjectId"
                    >
                        <option value="">
                            Ninguno
                        </option>

                        @foreach ($projects as $project)
                            <option
                                value="{{ $project->projectId }}"
                                @selected(
                                    $currentProjectId
                                    === $project->projectId
                                )
                            >
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>

                    @if ($selectedProjectId)
                        <span class="quote-field__hint">
                            Llegaste desde un proyecto
                            del portafolio; lo dejamos
                            seleccionado como referencia.
                        </span>
                    @endif
                </div>

                <div
                    class="quote-step__actions quote-step__actions--end"
                >
                    <button
                        class="public-button quote-next"
                        type="button"
                        data-next-step="2"
                        @disabled($services->isEmpty())
                    >
                        Continuar
                    </button>
                </div>
            </section>

            <section
                class="quote-step"
                data-quote-step="2"
            >
                <div class="quote-step__heading">
                    <span class="eyebrow">
                        PASO 02
                    </span>

                    <h2>
                        Cuéntanos sobre
                        el proyecto.
                    </h2>
                </div>

                <div class="quote-field">
                    <label for="description">
                        ¿Qué quieres realizar? *
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="7"
                        maxlength="5000"
                        required
                        placeholder="Describe medidas aproximadas, materiales, idea, problema a resolver o resultado que buscas."
                    >{{ old('description') }}</textarea>
                </div>

                <div class="quote-form-grid">
                    <div class="quote-field">
                        <label for="locationCity">
                            Ciudad / municipio *
                        </label>

                        <input
                            id="locationCity"
                            name="locationCity"
                            type="text"
                            maxlength="120"
                            value="{{ old('locationCity') }}"
                            required
                        >
                    </div>

                    <div class="quote-field">
                        <label for="locationState">
                            Estado *
                        </label>

                        <input
                            id="locationState"
                            name="locationState"
                            type="text"
                            maxlength="120"
                            value="{{
                                old(
                                    'locationState',
                                    'Estado de México'
                                )
                            }}"
                            required
                        >
                    </div>

                    <div class="quote-field">
                        <label for="locationNeighborhood">
                            Colonia / zona
                        </label>

                        <input
                            id="locationNeighborhood"
                            name="locationNeighborhood"
                            type="text"
                            maxlength="160"
                            value="{{
                                old(
                                    'locationNeighborhood'
                                )
                            }}"
                        >
                    </div>

                    <div class="quote-field">
                        <label for="preferredTimeframeId">
                            ¿Cuándo te gustaría realizarlo?
                        </label>

                        <select
                            id="preferredTimeframeId"
                            name="preferredTimeframeId"
                        >
                            <option value="">
                                Selecciona una opción
                            </option>

                            @foreach (
                                $preferredTimeframes
                                as $timeframe
                            )
                                <option
                                    value="{{
                                        $timeframe
                                            ->preferredTimeframeId
                                    }}"
                                    @selected(
                                        old(
                                            'preferredTimeframeId'
                                        )
                                        === $timeframe
                                            ->preferredTimeframeId
                                    )
                                >
                                    {{ $timeframe->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="quote-step__actions">
                    <button
                        class="quote-secondary-button"
                        type="button"
                        data-prev-step="1"
                    >
                        Atrás
                    </button>

                    <button
                        class="public-button quote-next"
                        type="button"
                        data-next-step="3"
                    >
                        Continuar
                    </button>
                </div>
            </section>

            <section
                class="quote-step"
                data-quote-step="3"
            >
                <div class="quote-step__heading">
                    <span class="eyebrow">
                        PASO 03
                    </span>

                    <h2>
                        ¿Cómo podemos contactarte?
                    </h2>

                    <p>
                        Necesitamos al menos WhatsApp,
                        teléfono o correo electrónico.
                    </p>
                </div>

                <div class="quote-form-grid">
                    <div class="quote-field">
                        <label for="firstName">
                            Nombre *
                        </label>

                        <input
                            id="firstName"
                            name="firstName"
                            type="text"
                            maxlength="120"
                            value="{{ old('firstName') }}"
                            required
                        >
                    </div>

                    <div class="quote-field">
                        <label for="lastName">
                            Apellidos
                        </label>

                        <input
                            id="lastName"
                            name="lastName"
                            type="text"
                            maxlength="160"
                            value="{{ old('lastName') }}"
                        >
                    </div>

                    <div class="quote-field">
                        <label for="whatsAppNumber">
                            WhatsApp
                        </label>

                        <input
                            id="whatsAppNumber"
                            name="whatsAppNumber"
                            type="tel"
                            maxlength="30"
                            value="{{ old('whatsAppNumber') }}"
                        >
                    </div>

                    <div class="quote-field">
                        <label for="phoneNumber">
                            Teléfono
                        </label>

                        <input
                            id="phoneNumber"
                            name="phoneNumber"
                            type="tel"
                            maxlength="30"
                            value="{{ old('phoneNumber') }}"
                        >
                    </div>

                    <div class="quote-field">
                        <label for="email">
                            Correo electrónico
                        </label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            maxlength="190"
                            value="{{ old('email') }}"
                        >
                    </div>

                    <div class="quote-field">
                        <label for="preferredContactMethodId">
                            Medio de contacto preferido
                        </label>

                        <select
                            id="preferredContactMethodId"
                            name="preferredContactMethodId"
                        >
                            <option value="">
                                Selecciona una opción
                            </option>

                            @foreach (
                                $contactMethods
                                as $contactMethod
                            )
                                <option
                                    value="{{
                                        $contactMethod
                                            ->contactMethodId
                                    }}"
                                    @selected(
                                        old(
                                            'preferredContactMethodId'
                                        )
                                        === $contactMethod
                                            ->contactMethodId
                                    )
                                >
                                    {{ $contactMethod->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="quote-step__actions">
                    <button
                        class="quote-secondary-button"
                        type="button"
                        data-prev-step="2"
                    >
                        Atrás
                    </button>

                    <button
                        class="public-button quote-next"
                        type="button"
                        data-next-step="4"
                    >
                        Continuar
                    </button>
                </div>
            </section>

            <section
                class="quote-step"
                data-quote-step="4"
            >
                <div class="quote-step__heading">
                    <span class="eyebrow">
                        PASO 04
                    </span>

                    <h2>
                        Archivos y referencias.
                    </h2>

                    <p>
                        Este paso es opcional.
                        Los archivos se almacenan
                        de forma privada y solo
                        personal autorizado puede verlos.
                    </p>
                </div>

                <div class="quote-file-grid">
                    <div class="quote-file-card">
                        <label for="currentSpaceFiles">
                            Fotografías del espacio actual
                        </label>

                        <input
                            id="currentSpaceFiles"
                            name="currentSpaceFiles[]"
                            type="file"
                            multiple
                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                        >

                        <small>
                            Hasta 6 archivos.
                        </small>
                    </div>

                    <div class="quote-file-card">
                        <label for="referenceImageFiles">
                            Imágenes de referencia
                        </label>

                        <input
                            id="referenceImageFiles"
                            name="referenceImageFiles[]"
                            type="file"
                            multiple
                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                        >

                        <small>
                            Ideas o resultados que te gustan.
                        </small>
                    </div>

                    <div class="quote-file-card">
                        <label for="blueprintFiles">
                            Planos
                        </label>

                        <input
                            id="blueprintFiles"
                            name="blueprintFiles[]"
                            type="file"
                            multiple
                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                        >
                    </div>

                    <div class="quote-file-card">
                        <label for="sketchFiles">
                            Croquis
                        </label>

                        <input
                            id="sketchFiles"
                            name="sketchFiles[]"
                            type="file"
                            multiple
                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                        >
                    </div>

                    <div
                        class="quote-file-card quote-file-card--wide"
                    >
                        <label for="documentFiles">
                            Otros documentos
                        </label>

                        <input
                            id="documentFiles"
                            name="documentFiles[]"
                            type="file"
                            multiple
                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                        >

                        <small>
                            JPG, PNG, WEBP o PDF.
                            Máximo 15 MB por archivo.
                        </small>
                    </div>
                </div>

                <div class="quote-submit-summary">
                    <div>
                        <strong>
                            Al enviar generaremos tu folio.
                        </strong>

                        <p>
                            La solicitud aparecerá
                            inmediatamente en nuestro
                            sistema de seguimiento.
                        </p>
                    </div>
                </div>

                <div class="quote-step__actions">
                    <button
                        class="quote-secondary-button"
                        type="button"
                        data-prev-step="3"
                    >
                        Atrás
                    </button>

                    <button
                        class="public-button"
                        type="submit"
                    >
                        Enviar solicitud
                    </button>
                </div>
            </section>
        </form>
    </div>
</section>

<script
    src="{{ asset('js/public-quote.js') }}"
    defer
></script>
@endsection
