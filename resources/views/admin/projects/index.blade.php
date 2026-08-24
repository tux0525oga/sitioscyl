@extends('admin.layouts.app')

@section('title', 'Proyectos')
@section('pageTitle', 'Proyectos')

@section('content')
<section class="page-heading">
    <div>
        <h1>Portafolio de proyectos</h1>
        <p>Administra los proyectos que después se mostrarán en el portafolio público de Somos Constructivos.</p>
    </div>

    <a class="button" href="{{ route('admin.projects.create') }}">
        Nuevo proyecto
    </a>
</section>

<form class="search-toolbar" method="GET" action="{{ route('admin.projects.index') }}">
    <input
        class="search-field"
        type="search"
        name="search"
        value="{{ $search }}"
        placeholder="Buscar por nombre, slug, ciudad o estado"
    >

    <button class="button" type="submit">Buscar</button>

    @if ($search !== '')
        <a class="button button--ghost" href="{{ route('admin.projects.index') }}">
            Limpiar
        </a>
    @endif
</form>

<section class="panel-card">
    <header class="panel-card__header">
        <div>
            <h2 class="panel-card__title">Registro de proyectos</h2>
            <p class="panel-card__subtitle">
                {{ $projects->total() }}
                {{ $projects->total() === 1 ? 'proyecto' : 'proyectos' }}
            </p>
        </div>
    </header>

    @if ($projects->isEmpty())
        <div class="empty-state">Todavía no hay proyectos registrados.</div>
    @else
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Proyecto</th>
                        <th>Servicios</th>
                        <th>Ubicación</th>
                        <th>Año</th>
                        <th>Estado</th>
                        <th>Orden</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                        <tr>
                            <td>
                                <a class="text-link table-primary" href="{{ route('admin.projects.edit', $project) }}">
                                    {{ $project->name }}
                                </a>
                                <span class="table-secondary">/{{ $project->slug }}</span>
                            </td>

                            <td>
                                @forelse ($project->serviceLinks as $serviceLink)
                                    <span class="table-primary">{{ $serviceLink->service->name }}</span>
                                    @if (!$loop->last)
                                        <span class="table-secondary">+</span>
                                    @endif
                                @empty
                                    —
                                @endforelse
                            </td>

                            <td>
                                {{ $project->locationCity ?: '—' }}
                                @if ($project->locationState)
                                    <span class="table-secondary">{{ $project->locationState }}</span>
                                @endif
                            </td>

                            <td>{{ $project->projectYear ?: '—' }}</td>

                            <td>
                                @if ($project->isPublished)
                                    <span class="status-badge" data-status="Accepted">Publicado</span>
                                @else
                                    <span class="status-badge" data-status="New">Borrador</span>
                                @endif

                                @if ($project->isFeatured)
                                    <span class="table-secondary">Destacado</span>
                                @endif
                            </td>

                            <td>{{ $project->displayOrder }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>
@endsection
