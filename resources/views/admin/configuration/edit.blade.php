@extends('admin.layouts.app')

@section('title', 'Configuración | Somos Constructivos')

@section('content')

<div class="admin-page">

    <div class="admin-page__header">
        <div>
            <span class="admin-eyebrow">
                SOMOS CONSTRUCTIVOS
            </span>

            <h1>Configuración</h1>

            <p>
                Información corporativa utilizada en el sitio público.
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="admin-alert admin-alert--success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="admin-alert admin-alert--error">
            <strong>
                Revisa la información capturada.
            </strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('admin.configuration.update') }}"
        class="admin-form"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')


        {{-- EMPRESA --}}
        <section class="admin-panel">

            <div class="admin-panel__heading">
                <div>
                    <span class="admin-eyebrow">
                        EMPRESA
                    </span>

                    <h2>Identidad corporativa</h2>

                    <p>
                        Datos principales de Somos Constructivos.
                    </p>
                </div>
            </div>

            <div class="admin-form-grid">

                <div class="admin-field">
                    <label for="companyName">
                        Nombre de la empresa *
                    </label>

                    <input
                        id="companyName"
                        name="companyName"
                        type="text"
                        maxlength="190"
                        required
                        value="{{ old(
                            'companyName',
                            $companyProfile->companyName
                        ) }}"
                    >
                </div>

                <div class="admin-field">
                    <label for="slogan">
                        Slogan
                    </label>

                    <input
                        id="slogan"
                        name="slogan"
                        type="text"
                        maxlength="255"
                        value="{{ old(
                            'slogan',
                            $companyProfile->slogan
                        ) }}"
                    >
                </div>

            </div>

        </section>


        {{-- CONTACTO --}}
        <section class="admin-panel">

            <div class="admin-panel__heading">
                <div>
                    <span class="admin-eyebrow">
                        CONTACTO
                    </span>

                    <h2>Canales de atención</h2>

                    <p>
                        Información que podrá mostrarse en el sitio público.
                    </p>
                </div>
            </div>

            <div class="admin-form-grid">

                <div class="admin-field">
                    <label for="phoneNumber">
                        Teléfono
                    </label>

                    <input
                        id="phoneNumber"
                        name="phoneNumber"
                        type="text"
                        maxlength="40"
                        value="{{ old(
                            'phoneNumber',
                            $companyProfile->phoneNumber
                        ) }}"
                    >
                </div>

                <div class="admin-field">
                    <label for="whatsAppNumber">
                        WhatsApp
                    </label>

                    <input
                        id="whatsAppNumber"
                        name="whatsAppNumber"
                        type="text"
                        maxlength="40"
                        value="{{ old(
                            'whatsAppNumber',
                            $companyProfile->whatsAppNumber
                        ) }}"
                    >
                </div>

                <div class="admin-field admin-field--wide">
                    <label for="contactEmail">
                        Correo electrónico
                    </label>

                    <input
                        id="contactEmail"
                        name="contactEmail"
                        type="email"
                        maxlength="190"
                        value="{{ old(
                            'contactEmail',
                            $companyProfile->contactEmail
                        ) }}"
                    >
                </div>

            </div>

        </section>


        {{-- UBICACIÓN --}}
        <section class="admin-panel">

            <div class="admin-panel__heading">
                <div>
                    <span class="admin-eyebrow">
                        UBICACIÓN
                    </span>

                    <h2>Domicilio</h2>

                    <p>
                        Dirección principal de la empresa.
                    </p>
                </div>
            </div>

            <div class="admin-form-grid">

                <div class="admin-field admin-field--wide">
                    <label for="addressLine">
                        Calle y número
                    </label>

                    <input
                        id="addressLine"
                        name="addressLine"
                        type="text"
                        maxlength="255"
                        value="{{ old(
                            'addressLine',
                            $companyProfile->addressLine
                        ) }}"
                    >
                </div>

                <div class="admin-field">
                    <label for="locationCity">
                        Ciudad / municipio
                    </label>

                    <input
                        id="locationCity"
                        name="locationCity"
                        type="text"
                        maxlength="120"
                        value="{{ old(
                            'locationCity',
                            $companyProfile->locationCity
                        ) }}"
                    >
                </div>

                <div class="admin-field">
                    <label for="locationState">
                        Estado
                    </label>

                    <input
                        id="locationState"
                        name="locationState"
                        type="text"
                        maxlength="120"
                        value="{{ old(
                            'locationState',
                            $companyProfile->locationState
                        ) }}"
                    >
                </div>

                <div class="admin-field">
                    <label for="postalCode">
                        Código postal
                    </label>

                    <input
                        id="postalCode"
                        name="postalCode"
                        type="text"
                        maxlength="20"
                        value="{{ old(
                            'postalCode',
                            $companyProfile->postalCode
                        ) }}"
                    >
                </div>

            </div>

        </section>


        {{-- HORARIOS --}}
        <section class="admin-panel">

            <div class="admin-panel__heading">
                <div>
                    <span class="admin-eyebrow">
                        ATENCIÓN
                    </span>

                    <h2>Horario</h2>

                    <p>
                        Horario general para atención a clientes.
                    </p>
                </div>
            </div>

            <div class="admin-field">
                <label for="businessHours">
                    Horario de atención
                </label>

                <textarea
                    id="businessHours"
                    name="businessHours"
                    rows="4"
                    maxlength="500"
                    placeholder="Ej. Lunes a viernes de 9:00 a 18:00 hrs."
                >{{ old(
                    'businessHours',
                    $companyProfile->businessHours
                ) }}</textarea>
            </div>

        </section>


        {{-- IDENTIDAD VISUAL --}}
        <section class="admin-panel">

            <div class="admin-panel__heading">
                <div>
                    <span class="admin-eyebrow">
                        IDENTIDAD VISUAL
                    </span>

                    <h2>
                        Logotipo, monograma y portada
                    </h2>

                    <p>
                        Activos corporativos y fotografía principal
                        utilizados en el sitio público.
                    </p>
                </div>
            </div>

            <div class="admin-identity-grid">

                <div class="admin-identity-card">

                    <div class="admin-identity-preview">
                        @if ($monogramUrl)
                            <img
                                src="{{ $monogramUrl }}"
                                alt="Monograma actual de Somos Constructivos"
                            >
                        @else
                            <span>Sin monograma configurado</span>
                        @endif
                    </div>

                    <div class="admin-field">
                        <label for="monogramFile">
                            Monograma
                        </label>

                        <input
                            id="monogramFile"
                            name="monogramFile"
                            type="file"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        >

                        <small class="admin-field__hint">
                            JPG, PNG o WEBP. Máximo 15 MB.
                        </small>
                    </div>

                </div>


                <div class="admin-identity-card">

                    <div class="admin-identity-preview admin-identity-preview--logo">
                        @if ($logoUrl)
                            <img
                                src="{{ $logoUrl }}"
                                alt="Logotipo actual de Somos Constructivos"
                            >
                        @else
                            <span>Sin logotipo configurado</span>
                        @endif
                    </div>

                    <div class="admin-field">
                        <label for="logoFile">
                            Logotipo
                        </label>

                        <input
                            id="logoFile"
                            name="logoFile"
                            type="file"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        >

                        <small class="admin-field__hint">
                            JPG, PNG o WEBP. Máximo 15 MB.
                        </small>
                    </div>

                </div>


                <div class="admin-identity-card">

                    <div class="admin-identity-preview admin-identity-preview--logo">
                        @if ($homeHeroUrl)
                            <img
                                src="{{ $homeHeroUrl }}"
                                alt="Portada actual de Inicio de Somos Constructivos"
                            >
                        @else
                            <span>
                                Sin portada configurada
                            </span>
                        @endif
                    </div>

                    <div class="admin-field">
                        <label for="homeHeroFile">
                            Imagen principal de Inicio
                        </label>

                        <input
                            id="homeHeroFile"
                            name="homeHeroFile"
                            type="file"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        >

                        <small class="admin-field__hint">
                            JPG, PNG o WEBP. Máximo 15 MB.
                            Recomendado: imagen horizontal de alta calidad.
                        </small>
                    </div>

                </div>

            </div>

        </section>


        <div class="admin-form__actions">

            <button
                type="submit"
                class="admin-button admin-button--primary"
            >
                Guardar configuración
            </button>

        </div>

    </form>

</div>

@endsection
