@php
    $editing = isset($service);

    $serviceSeo = $editing
        ? $service->seo
        : null;
@endphp

@if (session('success'))
    <div class="alert alert--success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert--error">
        <strong>Revisa la información del servicio.</strong>
        <ul class="form-error-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="service-form-grid">
    <div class="service-form-main">
        <section class="panel-card detail-card">
            <header class="panel-card__header">
                <div>
                    <h2 class="panel-card__title">Información general</h2>
                    <p class="panel-card__subtitle">Nombre, URL y descripción del servicio.</p>
                </div>
            </header>

            <div class="detail-card__body">
                <div class="form-field">
                    <label class="info-label" for="name">Nombre del servicio *</label>
                    <input
                        id="name"
                        class="admin-input"
                        name="name"
                        type="text"
                        maxlength="190"
                        value="{{ old('name', $editing ? $service->name : '') }}"
                        required
                    >
                </div>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="slug">Slug</label>
                    <input
                        id="slug"
                        class="admin-input"
                        name="slug"
                        type="text"
                        maxlength="190"
                        value="{{ old('slug', $editing ? $service->slug : '') }}"
                        placeholder="Se genera automáticamente si lo dejas vacío"
                    >
                </div>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="shortDescription">Descripción corta</label>
                    <textarea
                        id="shortDescription"
                        class="admin-textarea"
                        name="shortDescription"
                        rows="3"
                        maxlength="500"
                    >{{ old('shortDescription', $editing ? $service->shortDescription : '') }}</textarea>
                </div>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="description">Descripción completa</label>
                    <textarea
                        id="description"
                        class="admin-textarea"
                        name="description"
                        rows="8"
                    >{{ old('description', $editing ? $service->description : '') }}</textarea>
                </div>
            </div>
        </section>

        <section class="panel-card detail-card">
            <header class="panel-card__header">
                <div>
                    <h2 class="panel-card__title">Encabezado de la página</h2>
                    <p class="panel-card__subtitle">Texto principal que verá el cliente.</p>
                </div>
            </header>

            <div class="detail-card__body">
                <div class="form-field">
                    <label class="info-label" for="heroTitle">Título principal</label>
                    <input
                        id="heroTitle"
                        class="admin-input"
                        name="heroTitle"
                        type="text"
                        maxlength="255"
                        value="{{ old('heroTitle', $editing ? $service->heroTitle : '') }}"
                    >
                </div>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="heroSubtitle">Subtítulo</label>
                    <textarea
                        id="heroSubtitle"
                        class="admin-textarea"
                        name="heroSubtitle"
                        rows="4"
                        maxlength="500"
                    >{{ old('heroSubtitle', $editing ? $service->heroSubtitle : '') }}</textarea>
                </div>
            </div>
        </section>

        <section class="panel-card detail-card">
            <header class="panel-card__header">
                <div>
                    <h2 class="panel-card__title">SEO y buscadores</h2>
                    <p class="panel-card__subtitle">
                        Información para Google y para compartir este servicio.
                    </p>
                </div>
            </header>

            <div class="detail-card__body">
                <div class="form-field">
                    <label class="info-label" for="seoMetaTitle">
                        Título SEO
                    </label>
                    <input
                        id="seoMetaTitle"
                        class="admin-input"
                        name="seo[metaTitle]"
                        type="text"
                        maxlength="180"
                        value="{{ old('seo.metaTitle', $serviceSeo?->metaTitle) }}"
                        placeholder="Si lo dejas vacío se usará el nombre del servicio"
                    >
                </div>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="seoMetaDescription">
                        Descripción para Google
                    </label>
                    <textarea
                        id="seoMetaDescription"
                        class="admin-textarea"
                        name="seo[metaDescription]"
                        rows="4"
                        maxlength="320"
                        placeholder="Descripción breve para resultados de búsqueda"
                    >{{ old('seo.metaDescription', $serviceSeo?->metaDescription) }}</textarea>
                </div>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="seoCanonicalUrl">
                        URL canónica
                    </label>
                    <input
                        id="seoCanonicalUrl"
                        class="admin-input"
                        name="seo[canonicalUrl]"
                        type="text"
                        maxlength="500"
                        value="{{ old('seo.canonicalUrl', $serviceSeo?->canonicalUrl) }}"
                        placeholder="Opcional"
                    >
                </div>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="seoSocialTitle">
                        Título para compartir
                    </label>
                    <input
                        id="seoSocialTitle"
                        class="admin-input"
                        name="seo[socialTitle]"
                        type="text"
                        maxlength="180"
                        value="{{ old('seo.socialTitle', $serviceSeo?->socialTitle) }}"
                        placeholder="WhatsApp, Facebook y otras redes"
                    >
                </div>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="seoSocialDescription">
                        Descripción para compartir
                    </label>
                    <textarea
                        id="seoSocialDescription"
                        class="admin-textarea"
                        name="seo[socialDescription]"
                        rows="3"
                        maxlength="320"
                    >{{ old('seo.socialDescription', $serviceSeo?->socialDescription) }}</textarea>
                </div>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="seoSocialImageId">
                        Imagen social
                    </label>
                    <select
                        id="seoSocialImageId"
                        class="admin-input"
                        name="seo[socialImageId]"
                    >
                        <option value="">
                            Automática / sin seleccionar
                        </option>

                        @foreach ($seoMediaAssets as $mediaAsset)
                            <option
                                value="{{ $mediaAsset->mediaId }}"
                                @selected(
                                    old(
                                        'seo.socialImageId',
                                        $serviceSeo?->socialImageId
                                    ) === $mediaAsset->mediaId
                                )
                            >
                                {{
                                    $mediaAsset->title
                                    ?: $mediaAsset->originalFileName
                                    ?: $mediaAsset->fileName
                                }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field form-field--spaced">
                    <input
                        type="hidden"
                        name="seo[robotsIndex]"
                        value="0"
                    >
                    <label class="toggle-row">
                        <input
                            type="checkbox"
                            name="seo[robotsIndex]"
                            value="1"
                            @checked(
                                (bool) old(
                                    'seo.robotsIndex',
                                    $serviceSeo?->robotsIndex ?? true
                                )
                            )
                        >
                        <span>
                            Permitir que Google indexe esta página
                        </span>
                    </label>

                    <input
                        type="hidden"
                        name="seo[robotsFollow]"
                        value="0"
                    >
                    <label class="toggle-row">
                        <input
                            type="checkbox"
                            name="seo[robotsFollow]"
                            value="1"
                            @checked(
                                (bool) old(
                                    'seo.robotsFollow',
                                    $serviceSeo?->robotsFollow ?? true
                                )
                            )
                        >
                        <span>
                            Permitir que Google siga los enlaces
                        </span>
                    </label>
                </div>
            </div>
        </section>
    </div>

    <aside class="service-form-aside">
        <section class="panel-card detail-card">
            <header class="panel-card__header">
                <div>
                    <h2 class="panel-card__title">Publicación</h2>
                </div>
            </header>

            <div class="detail-card__body">
                <input type="hidden" name="isPublished" value="0">
                <label class="toggle-row">
                    <input
                        type="checkbox"
                        name="isPublished"
                        value="1"
                        @checked((bool) old('isPublished', $editing ? $service->isPublished : false))
                    >
                    <span>Servicio publicado</span>
                </label>

                <input type="hidden" name="isFeatured" value="0">
                <label class="toggle-row">
                    <input
                        type="checkbox"
                        name="isFeatured"
                        value="1"
                        @checked((bool) old('isFeatured', $editing ? $service->isFeatured : false))
                    >
                    <span>Servicio destacado</span>
                </label>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="displayOrder">Orden</label>
                    <input
                        id="displayOrder"
                        class="admin-input"
                        name="displayOrder"
                        type="number"
                        min="0"
                        max="65535"
                        value="{{ old('displayOrder', $editing ? $service->displayOrder : 0) }}"
                    >
                </div>
            </div>
        </section>

        <button class="button button--full" type="submit">
            {{ $editing ? 'Guardar cambios' : 'Crear servicio' }}
        </button>
    </aside>
</div>
