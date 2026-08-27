@extends('admin.layouts.app')

@section('title', 'Multimedia | Somos Constructivos')

@section('content')

<div class="admin-page">

    <div class="admin-page__header">
        <div>
            <span class="admin-eyebrow">
                BIBLIOTECA
            </span>

            <h1>Multimedia</h1>

            <p>
                Consulta los archivos utilizados en proyectos,
                servicios e identidad visual.
            </p>
        </div>
    </div>


    {{-- BUSCADOR Y FILTROS --}}
    <section class="admin-media-toolbar">

        <form
            method="GET"
            action="{{ route('admin.media.index') }}"
            class="admin-media-search"
        >
            <div class="admin-field">
                <label for="mediaSearch">
                    Buscar archivo
                </label>

                <input
                    id="mediaSearch"
                    name="search"
                    type="search"
                    value="{{ $search }}"
                    placeholder="Título o nombre del archivo..."
                >
            </div>

            @if ($usage !== '')
                <input
                    type="hidden"
                    name="usage"
                    value="{{ $usage }}"
                >
            @endif

            <button
                type="submit"
                class="admin-button admin-button--primary"
            >
                Buscar
            </button>

            @if ($search !== '')
                <a
                    class="admin-button admin-button--secondary"
                    href="{{ route(
                        'admin.media.index',
                        $usage !== ''
                            ? ['usage' => $usage]
                            : []
                    ) }}"
                >
                    Limpiar
                </a>
            @endif
        </form>


        <nav
            class="admin-media-filters"
            aria-label="Filtrar biblioteca multimedia"
        >
            <a
                class="admin-media-filter {{
                    $usage === ''
                        ? 'is-active'
                        : ''
                }}"
                href="{{ route('admin.media.index') }}"
            >
                Todos
            </a>

            <a
                class="admin-media-filter {{
                    $usage === 'project'
                        ? 'is-active'
                        : ''
                }}"
                href="{{ route(
                    'admin.media.index',
                    ['usage' => 'project']
                ) }}"
            >
                Proyectos
            </a>

            <a
                class="admin-media-filter {{
                    $usage === 'service'
                        ? 'is-active'
                        : ''
                }}"
                href="{{ route(
                    'admin.media.index',
                    ['usage' => 'service']
                ) }}"
            >
                Servicios
            </a>

            <a
                class="admin-media-filter {{
                    $usage === 'identity'
                        ? 'is-active'
                        : ''
                }}"
                href="{{ route(
                    'admin.media.index',
                    ['usage' => 'identity']
                ) }}"
            >
                Identidad visual
            </a>

            <a
                class="admin-media-filter {{
                    $usage === 'unlinked'
                        ? 'is-active'
                        : ''
                }}"
                href="{{ route(
                    'admin.media.index',
                    ['usage' => 'unlinked']
                ) }}"
            >
                Sin utilizar
            </a>
        </nav>

    </section>


    {{-- RESULTADOS --}}
    @if ($mediaAssets->isEmpty())

        <section class="admin-empty-state">
            <strong>
                No encontramos archivos.
            </strong>

            <p>
                Cambia los filtros o realiza otra búsqueda.
            </p>
        </section>

    @else

        <div class="admin-media-grid">

            @foreach ($mediaAssets as $mediaAsset)

                @php
                    $isLogo =
                        $companyProfile?->logoMediaId
                        === $mediaAsset->mediaId;

                    $isMonogram =
                        $companyProfile?->monogramMediaId
                        === $mediaAsset->mediaId;

                    $mediaUrl =
                        $mediaUrls[$mediaAsset->mediaId]
                        ?? null;
                @endphp

                <article class="admin-media-card">

                    <div class="admin-media-card__preview">

                        @if ($mediaUrl)
                            <img
                                src="{{ $mediaUrl }}"
                                alt="{{
                                    $mediaAsset->altText
                                    ?: $mediaAsset->title
                                    ?: 'Archivo multimedia'
                                }}"
                                loading="lazy"
                            >
                        @else
                            <span>
                                Sin vista previa
                            </span>
                        @endif

                    </div>


                    <div class="admin-media-card__body">

                        <div class="admin-media-card__badges">

                            @if ($isMonogram)
                                <span
                                    class="
                                        admin-media-badge
                                        admin-media-badge--identity
                                    "
                                >
                                    Monograma
                                </span>
                            @endif

                            @if ($isLogo)
                                <span
                                    class="
                                        admin-media-badge
                                        admin-media-badge--identity
                                    "
                                >
                                    Logotipo
                                </span>
                            @endif

                            @if (
                                $mediaAsset->project_links_count > 0
                            )
                                <span class="admin-media-badge">
                                    Proyecto
                                </span>
                            @endif

                            @if (
                                $mediaAsset->service_links_count > 0
                            )
                                <span class="admin-media-badge">
                                    Servicio
                                </span>
                            @endif

                            @if (
                                !$isLogo
                                && !$isMonogram
                                && $mediaAsset->project_links_count === 0
                                && $mediaAsset->service_links_count === 0
                            )
                                <span
                                    class="
                                        admin-media-badge
                                        admin-media-badge--muted
                                    "
                                >
                                    Sin utilizar
                                </span>
                            @endif

                        </div>


                        <h2>
                            {{
                                $mediaAsset->title
                                ?: $mediaAsset->originalFileName
                                ?: $mediaAsset->fileName
                            }}
                        </h2>


                        <dl class="admin-media-meta">

                            <div>
                                <dt>Archivo</dt>
                                <dd>
                                    {{
                                        $mediaAsset->originalFileName
                                        ?: $mediaAsset->fileName
                                    }}
                                </dd>
                            </div>

                            <div>
                                <dt>Dimensiones</dt>
                                <dd>
                                    @if (
                                        $mediaAsset->width
                                        && $mediaAsset->height
                                    )
                                        {{
                                            $mediaAsset->width
                                        }} × {{
                                            $mediaAsset->height
                                        }} px
                                    @else
                                        —
                                    @endif
                                </dd>
                            </div>

                            <div>
                                <dt>Tipo</dt>
                                <dd>
                                    {{
                                        strtoupper(
                                            $mediaAsset->fileExtension
                                            ?: ''
                                        )
                                    }}
                                </dd>
                            </div>

                            <div>
                                <dt>Estado</dt>
                                <dd>
                                    {{
                                        $mediaAsset->isPublished
                                            ? 'Publicado'
                                            : 'No publicado'
                                    }}
                                </dd>
                            </div>

                        </dl>

                    </div>

                </article>

            @endforeach

        </div>


        @if ($mediaAssets->hasPages())
            <div class="admin-media-pagination">
                {{ $mediaAssets->links() }}
            </div>
        @endif

    @endif

</div>

@endsection