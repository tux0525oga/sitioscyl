<section class="panel-card detail-card service-content-section">
    <header class="panel-card__header">
        <div>
            <h2 class="panel-card__title">
                Galería del servicio
            </h2>

            <p class="panel-card__subtitle">
                Fotografías públicas para la página
                del servicio.
            </p>
        </div>
    </header>

    <div class="detail-card__body">
        <form
            method="POST"
            enctype="multipart/form-data"
            action="{{
                route(
                    'admin.services.media.store',
                    $service
                )
            }}"
        >
            @csrf

            <div class="form-grid">
                <div class="form-field form-field--wide">
                    <label class="info-label">
                        Imagen *
                    </label>

                    <input
                        class="admin-input admin-file-input"
                        name="file"
                        type="file"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        required
                    >

                    <span class="form-help">
                        JPG, PNG o WEBP. Máximo 15 MB.
                    </span>
                </div>

                <div class="form-field">
                    <label class="info-label">
                        Categoría
                    </label>

                    <select
                        class="admin-select"
                        name="mediaCategoryId"
                    >
                        <option value="">
                            Sin categoría
                        </option>

                        @foreach ($mediaCategories as $category)
                            <option
                                value="{{ $category->mediaCategoryId }}"
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-field">
                    <label class="info-label">
                        Orden
                    </label>

                    <input
                        class="admin-input"
                        name="displayOrder"
                        type="number"
                        min="0"
                        max="65535"
                        value="0"
                    >
                </div>

                <div class="form-field form-field--wide">
                    <label class="info-label">
                        Título
                    </label>

                    <input
                        class="admin-input"
                        name="title"
                        type="text"
                        maxlength="255"
                    >
                </div>

                <div class="form-field form-field--wide">
                    <label class="info-label">
                        Texto alternativo
                    </label>

                    <input
                        class="admin-input"
                        name="altText"
                        type="text"
                        maxlength="255"
                    >
                </div>

                <div class="form-field form-field--wide">
                    <label class="info-label">
                        Descripción
                    </label>

                    <textarea
                        class="admin-textarea"
                        name="description"
                        rows="3"
                        maxlength="2000"
                    ></textarea>
                </div>
            </div>

            <div class="media-upload-footer">
                <label class="toggle-row media-feature-toggle">
                    <input
                        type="checkbox"
                        name="isFeatured"
                        value="1"
                    >

                    <span>
                        Usar como imagen principal
                    </span>
                </label>

                <button class="button" type="submit">
                    Subir imagen
                </button>
            </div>
        </form>
    </div>

    @if ($serviceMediaItems->isEmpty())
        <div class="empty-state">
            Este servicio todavía no tiene imágenes.
        </div>
    @else
        <div class="project-media-grid">
            @foreach ($serviceMediaItems as $serviceMedia)
                @php
                    $asset = $serviceMediaMap->get(
                        $serviceMedia->mediaId
                    );

                    $category = $serviceMediaCategoryMap->get(
                        $serviceMedia->mediaCategoryId
                    );

                    $url = $asset
                        ? \Illuminate\Support\Facades\Storage::disk(
                            $asset->storageDisk
                        )->url(
                            $asset->storagePath
                        )
                        : null;
                @endphp

                @if ($asset && $url)
                    <article class="project-media-card">
                        <div class="project-media-card__image">
                            <img
                                src="{{ $url }}"
                                alt="{{
                                    $asset->altText
                                        ?: $service->name
                                }}"
                                loading="lazy"
                            >

                            @if ($serviceMedia->isFeatured)
                                <span class="media-featured-badge">
                                    Principal
                                </span>
                            @endif

                            @if ($category)
                                <span class="media-category-badge">
                                    {{ $category->name }}
                                </span>
                            @endif
                        </div>

                        <div class="project-media-card__body">
                            <strong>
                                {{
                                    $asset->title
                                        ?: $asset->originalFileName
                                }}
                            </strong>

                            @if ($asset->altText)
                                <span class="table-secondary">
                                    {{ $asset->altText }}
                                </span>
                            @endif

                            <span class="table-secondary">
                                Orden
                                {{ $serviceMedia->displayOrder }}
                            </span>
                        </div>

                        <div class="project-media-card__actions">
                            @if (!$serviceMedia->isFeatured)
                                <form
                                    method="POST"
                                    action="{{
                                        route(
                                            'admin.services.media.feature',
                                            [
                                                $service,
                                                $serviceMedia,
                                            ]
                                        )
                                    }}"
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
                                action="{{
                                    route(
                                        'admin.services.media.destroy',
                                        [
                                            $service,
                                            $serviceMedia,
                                        ]
                                    )
                                }}"
                                onsubmit="return confirm('¿Quitar esta imagen del servicio?');"
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
                @endif
            @endforeach
        </div>
    @endif
</section>
