@extends('admin.layouts.app')

@section('title', 'Servicios')
@section('pageTitle', 'Servicios')

@section('content')
<section class="page-heading">
    <div>
        <h1>Servicios</h1>
        <p>
            Administra el contenido comercial de las páginas públicas de servicios.
        </p>
    </div>

    <a class="button" href="{{ route('admin.services.create') }}">
        Nuevo servicio
    </a>
</section>

<form
    class="search-toolbar"
    method="GET"
    action="{{ route('admin.services.index') }}"
>
    <input
        class="search-field"
        type="search"
        name="search"
        value="{{ $search }}"
        placeholder="Buscar por nombre, slug o descripción"
    >

    <button class="button" type="submit">
        Buscar
    </button>

    @if ($search !== '')
        <a
            class="button button--ghost"
            href="{{ route('admin.services.index') }}"
        >
            Limpiar
        </a>
    @endif
</form>

<section class="panel-card">
    <header class="panel-card__header">
        <div>
            <h2 class="panel-card__title">
                Catálogo de servicios
            </h2>

            <p class="panel-card__subtitle">
                {{ $services->total() }}
                {{ $services->total() === 1 ? 'servicio' : 'servicios' }}
            </p>
        </div>
    </header>

    @if ($services->isEmpty())
        <div class="empty-state">
            No hay servicios registrados.
        </div>
    @else
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Servicio</th>
                        <th>Resumen</th>
                        <th>Estado</th>
                        <th>Destacado</th>
                        <th>Orden</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($services as $service)
                        <tr>
                            <td>
                                <a
                                    class="text-link table-primary"
                                    href="{{ route('admin.services.edit', $service) }}"
                                >
                                    {{ $service->name }}
                                </a>

                                <span class="table-secondary">
                                    /{{ $service->slug }}
                                </span>
                            </td>

                            <td>
                                {{ $service->shortDescription ?: '—' }}
                            </td>

                            <td>
                                @if ($service->isPublished)
                                    <span
                                        class="status-badge"
                                        data-status="Accepted"
                                    >
                                        Publicado
                                    </span>
                                @else
                                    <span
                                        class="status-badge"
                                        data-status="New"
                                    >
                                        Borrador
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $service->isFeatured ? 'Sí' : 'No' }}
                            </td>

                            <td>
                                {{ $service->displayOrder }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <footer class="pagination-bar">
            <div class="pagination-bar__meta">
                Página {{ $services->currentPage() }}
                de {{ $services->lastPage() }}
            </div>

            <div class="pagination-actions">
                <a
                    class="pagination-link {{ $services->onFirstPage() ? 'is-disabled' : '' }}"
                    href="{{ $services->previousPageUrl() ?: '#' }}"
                >
                    Anterior
                </a>

                <a
                    class="pagination-link {{ $services->hasMorePages() ? '' : 'is-disabled' }}"
                    href="{{ $services->nextPageUrl() ?: '#' }}"
                >
                    Siguiente
                </a>
            </div>
        </footer>
    @endif
</section>
@endsection