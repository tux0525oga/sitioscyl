@php
    $projectMediaItems = $project->mediaLinks
        ->sortBy('displayOrder')
        ->values();
@endphp

<section class="panel-card detail-card project-media-section">
    <header class="panel-card__header">
        <div>
            <h2 class="panel-card__title">Multimedia del proyecto</h2>
            <p class="panel-card__subtitle">
                Imagen principal, galería, clasificación y metadatos de accesibilidad/SEO.
            </p>
        </div>
    </header>

    <div class="detail-card__body">
        <form
            class="media-upload-form"
            method="POST"
            enctype="multipart/form-data"
            action="{{ route('admin.projects.media.store', $project) }}"
        >
            @csrf

            <div class="form-grid">
                <div class="form-field form-field--wide">
                    <label class="info-label" for="projectMediaFile">Imagen *</label>
                    <input
                        id="projectMediaFile"
                        class="admin-input admin-file-input"
                        name="file"
                        type="file"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        required
                    >
                    <span class="form-help">JPG, PNG o WEBP. Máximo 15 MB.</span>
                </div>

                <div class="form-field">
                    <label class="info-label" for="mediaCategoryId">Categoría</label>
                    <select
                        id="mediaCategoryId"
                        class="admin-select"
                        name="mediaCategoryId"
                    >
                        <option value="">Sin categoría</option>
                        @foreach ($mediaCategories as $mediaCategory)
                            <option value="{{ $mediaCategory->mediaCategoryId }}">
                                {{ $mediaCategory->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label class="info-label" for="mediaDisplayOrder">Orden</label>
                    <input
                        id="mediaDisplayOrder"
                        class="admin-input"
                        name="displayOrder"
                        type="number"
                        min="0"
                        max="65535"
                        value="0"
                    >
                </div>

                <div class="form-field form-field--wide">
                    <label class="info-label" for="mediaTitle">Título</label>
                    <input
                        id="mediaTitle"
                        class="admin-input"
                        name="title"
                        type="text"
                        maxlength="255"
                    >
                </div>

                <div class="form-field form-field--wide">
                    <label class="info-label" for="mediaAltText">Texto alternativo</label>
                    <input
                        id="mediaAltText"
                        class="admin-input"
                        name="altText"
                        type="text"
                        maxlength="255"
                        placeholder="Describe brevemente lo que se ve en la imagen"
                    >
                </div>

                <div class="form-field form-field--wide">
                    <label class="info-label" for="mediaDescription">Descripción</label>
                    <textarea
                        id="mediaDescription"
                        class="admin-textarea"
                        name="description"
                        rows="3"
                        maxlength="2000"
                    ></textarea>
                </div>
            </div>

            <div class="media-upload-footer">
                <label class="toggle-row media-feature-toggle">
                    <input type="checkbox" name="isFeatured" value="1">
                    <span>Usar como imagen principal</span>
                </label>

                <button class="button" type="submit">Subir imagen</button>
            </div>
        </form>
    </div>

    @if ($projectMediaItems->isEmpty())
        <div class="empty-state">Este proyecto todavía no tiene imágenes.</div>
    @else
        <div class="project-media-grid">
            @foreach ($projectMediaItems as $projectMedia)
                @php
                    $mediaAsset = $projectMedia->mediaAsset;
                    $mediaUrl = \Illuminate\Support\Facades\Storage::disk(
                        $mediaAsset->storageDisk
                    )->url($mediaAsset->storagePath);
                @endphp

                <article class="project-media-card">
                    <div class="project-media-card__image">
                        <img
                            src="{{ $mediaUrl }}"
                            alt="{{ $mediaAsset->altText ?: $project->name }}"
                            loading="lazy"
                        >

                        @if ($projectMedia->isFeatured)
                            <span class="media-featured-badge">Principal</span>
                        @endif

                        @if ($projectMedia->mediaCategory)
                            <span class="media-category-badge">
                                {{ $projectMedia->mediaCategory->name }}
                            </span>
                        @endif
                    </div>

                    <form
                        class="project-media-card__body"
                        method="POST"
                        action="{{ route('admin.projects.media.update', [$project, $projectMedia]) }}"
                    >
                        @csrf
                        @method('PUT')

                        <div class="form-field">
                            <label class="info-label">Categoría</label>
                            <select class="admin-select" name="mediaCategoryId">
                                <option value="">Sin categoría</option>
                                @foreach ($mediaCategories as $mediaCategory)
                                    <option
                                        value="{{ $mediaCategory->mediaCategoryId }}"
                                        @selected(
                                            $projectMedia->mediaCategoryId
                                            === $mediaCategory->mediaCategoryId
                                        )
                                    >
                                        {{ $mediaCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-field form-field--spaced">
                            <label class="info-label">Orden</label>
                            <input
                                class="admin-input"
                                name="displayOrder"
                                type="number"
                                min="0"
                                max="65535"
                                value="{{ $projectMedia->displayOrder }}"
                            >
                        </div>

                        <div class="form-field form-field--spaced">
                            <label class="info-label">Título</label>
                            <input
                                class="admin-input"
                                name="title"
                                type="text"
                                maxlength="255"
                                value="{{ $mediaAsset->title }}"
                            >
                        </div>

                        <div class="form-field form-field--spaced">
                            <label class="info-label">Texto alternativo</label>
                            <input
                                class="admin-input"
                                name="altText"
                                type="text"
                                maxlength="255"
                                value="{{ $mediaAsset->altText }}"
                            >
                        </div>

                        <div class="form-field form-field--spaced">
                            <label class="info-label">Descripción</label>
                            <textarea
                                class="admin-textarea"
                                name="description"
                                rows="3"
                                maxlength="2000"
                            >{{ $mediaAsset->description }}</textarea>
                        </div>

                        <button
                            class="button button--full media-save-button"
                            type="submit"
                        >
                            Guardar datos
                        </button>
                    </form>

                    <div class="project-media-card__actions">
                        @if (!$projectMedia->isFeatured)
                            <form
                                method="POST"
                                action="{{ route('admin.projects.media.feature', [$project, $projectMedia]) }}"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    class="button button--ghost button--small"
                                    type="submit"
                                >
                                    Hacer principal
                                </button>
                            </form>
                        @endif

                        <form
                            method="POST"
                            action="{{ route('admin.projects.media.destroy', [$project, $projectMedia]) }}"
                            onsubmit="return confirm('¿Quitar esta imagen del proyecto?');"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                class="button button--danger button--small"
                                type="submit"
                            >
                                Quitar
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>

<section class="panel-card detail-card project-comparison-section">
    <header class="panel-card__header">
        <div>
            <h2 class="panel-card__title">Antes / Después</h2>
            <p class="panel-card__subtitle">
                Crea comparaciones con imágenes que ya pertenezcan al proyecto.
            </p>
        </div>
    </header>

    @if ($projectMediaItems->count() < 2)
        <div class="empty-state">
            Necesitas al menos dos imágenes para crear una comparación.
        </div>
    @else
        <div class="detail-card__body">
            <form
                class="comparison-form"
                method="POST"
                action="{{ route('admin.projects.comparisons.store', $project) }}"
            >
                @csrf

                <div class="form-grid">
                    <div class="form-field">
                        <label class="info-label">Imagen Antes *</label>
                        <select
                            class="admin-select"
                            name="beforeProjectMediaId"
                            required
                        >
                            <option value="">Selecciona una imagen</option>
                            @foreach ($projectMediaItems as $projectMedia)
                                <option value="{{ $projectMedia->projectMediaId }}">
                                    {{
                                        $projectMedia->mediaAsset->title
                                        ?: $projectMedia->mediaAsset->originalFileName
                                    }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-field">
                        <label class="info-label">Imagen Después *</label>
                        <select
                            class="admin-select"
                            name="afterProjectMediaId"
                            required
                        >
                            <option value="">Selecciona una imagen</option>
                            @foreach ($projectMediaItems as $projectMedia)
                                <option value="{{ $projectMedia->projectMediaId }}">
                                    {{
                                        $projectMedia->mediaAsset->title
                                        ?: $projectMedia->mediaAsset->originalFileName
                                    }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-field form-field--wide">
                        <label class="info-label">Título</label>
                        <input
                            class="admin-input"
                            name="title"
                            type="text"
                            maxlength="255"
                        >
                    </div>

                    <div class="form-field form-field--wide">
                        <label class="info-label">Descripción</label>
                        <textarea
                            class="admin-textarea"
                            name="description"
                            rows="3"
                            maxlength="2000"
                        ></textarea>
                    </div>

                    <div class="form-field">
                        <label class="info-label">Orden</label>
                        <input
                            class="admin-input"
                            name="displayOrder"
                            type="number"
                            min="0"
                            max="65535"
                            value="0"
                        >
                    </div>

                    <div class="form-field">
                        <span class="info-label">Publicación</span>
                        <input type="hidden" name="isPublished" value="0">

                        <label class="toggle-row">
                            <input type="checkbox" name="isPublished" value="1">
                            <span>Comparación publicada</span>
                        </label>
                    </div>
                </div>

                <div class="form-actions">
                    <button class="button" type="submit">
                        Crear comparación
                    </button>
                </div>
            </form>
        </div>
    @endif

    @if ($project->comparisons->isNotEmpty())
        <div class="comparison-list">
            @foreach ($project->comparisons as $comparison)
                @php
                    $beforeUrl = \Illuminate\Support\Facades\Storage::disk(
                        $comparison->beforeImage->storageDisk
                    )->url($comparison->beforeImage->storagePath);

                    $afterUrl = \Illuminate\Support\Facades\Storage::disk(
                        $comparison->afterImage->storageDisk
                    )->url($comparison->afterImage->storagePath);
                @endphp

                <article class="comparison-card">
                    <div class="comparison-card__images">
                        <figure>
                            <img src="{{ $beforeUrl }}" alt="Antes" loading="lazy">
                            <figcaption>Antes</figcaption>
                        </figure>

                        <figure>
                            <img src="{{ $afterUrl }}" alt="Después" loading="lazy">
                            <figcaption>Después</figcaption>
                        </figure>
                    </div>

                    <div class="comparison-card__body">
                        <div>
                            <strong>{{ $comparison->title ?: 'Comparación' }}</strong>

                            @if ($comparison->description)
                                <p>{{ $comparison->description }}</p>
                            @endif

                            <span class="table-secondary">
                                {{ $comparison->isPublished ? 'Publicada' : 'Borrador' }}
                                · Orden {{ $comparison->displayOrder }}
                            </span>
                        </div>

                        <form
                            method="POST"
                            action="{{ route('admin.projects.comparisons.destroy', [$project, $comparison]) }}"
                            onsubmit="return confirm('¿Eliminar esta comparación?');"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                class="button button--danger button--small"
                                type="submit"
                            >
                                Eliminar
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
