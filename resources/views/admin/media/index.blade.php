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
            @php
                $currentPage = $mediaAssets->currentPage();
                $lastPage = $mediaAssets->lastPage();

                $visiblePages = collect([
                    1,
                    $currentPage - 2,
                    $currentPage - 1,
                    $currentPage,
                    $currentPage + 1,
                    $currentPage + 2,
                    $lastPage,
                ])
                    ->filter(fn ($page) => $page >= 1 && $page <= $lastPage)
                    ->unique()
                    ->sort()
                    ->values();
            @endphp

            <nav
                class="pagination-bar admin-media-pagination"
                aria-label="Paginación de multimedia"
            >
                <span class="pagination-bar__meta">
                    Mostrando {{ $mediaAssets->firstItem() }}
                    – {{ $mediaAssets->lastItem() }}
                    de {{ $mediaAssets->total() }}
                </span>

                <div class="pagination-actions">
                    @if ($mediaAssets->onFirstPage())
                        <span
                            class="pagination-link is-disabled"
                            aria-hidden="true"
                        >
                            ‹
                        </span>
                    @else
                        <a
                            class="pagination-link"
                            href="{{ $mediaAssets->previousPageUrl() }}"
                            rel="prev"
                            aria-label="Página anterior"
                        >
                            ‹
                        </a>
                    @endif

                    @php $previousPage = null; @endphp

                    @foreach ($visiblePages as $page)
                        @if (
                            $previousPage !== null
                            && $page > $previousPage + 1
                        )
                            <span
                                class="pagination-ellipsis"
                                aria-hidden="true"
                            >
                                …
                            </span>
                        @endif

                        @if ($page === $currentPage)
                            <span
                                class="pagination-link is-active"
                                aria-current="page"
                            >
                                {{ $page }}
                            </span>
                        @else
                            <a
                                class="pagination-link"
                                href="{{ $mediaAssets->url($page) }}"
                                aria-label="Ir a la página {{ $page }}"
                            >
                                {{ $page }}
                            </a>
                        @endif

                        @php $previousPage = $page; @endphp
                    @endforeach

                    @if ($mediaAssets->hasMorePages())
                        <a
                            class="pagination-link"
                            href="{{ $mediaAssets->nextPageUrl() }}"
                            rel="next"
                            aria-label="Página siguiente"
                        >
                            ›
                        </a>
                    @else
                        <span
                            class="pagination-link is-disabled"
                            aria-hidden="true"
                        >
                            ›
                        </span>
                    @endif
                </div>
            </nav>
        @endif

    @endif

</div>

@endsection