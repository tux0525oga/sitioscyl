@extends('admin.layouts.app')

@section('title', 'Cotizaciones')
@section('pageTitle', 'Cotizaciones')

@section('content')
    <section class="page-heading">
        <div>
            <h1>Solicitudes de cotización</h1>
            <p>
                Consulta folios, clientes, servicios solicitados
                y estado comercial de cada oportunidad.
            </p>
        </div>
    </section>

    <form
        class="search-toolbar"
        method="GET"
        action="{{ route('admin.quotes.index') }}"
    >
        <input
            class="search-field"
            type="search"
            name="search"
            value="{{ $search }}"
            placeholder="Buscar por folio, nombre, correo, teléfono o WhatsApp"
        >

        <button class="button" type="submit">
            Buscar
        </button>

        @if ($search !== '')
            <a
                class="button button--ghost"
                href="{{ route('admin.quotes.index') }}"
            >
                Limpiar
            </a>
        @endif
    </form>

    <section class="panel-card">
        <header class="panel-card__header">
            <div>
                <h2 class="panel-card__title">
                    Registro comercial
                </h2>

                <p class="panel-card__subtitle">
                    {{ $quotes->total() }}
                    {{ $quotes->total() === 1 ? 'resultado' : 'resultados' }}
                </p>
            </div>
        </header>

        @if ($quotes->isEmpty())
            <div class="empty-state">
                No encontramos cotizaciones con esos criterios.
            </div>
        @else
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Folio</th>
                            <th>Cliente</th>
                            <th>Servicios</th>
                            <th>Estado</th>
                            <th>Ubicación</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($quotes as $quote)
                            <tr>
                                <td>
                                    <span class="table-primary">
                                        {{ $quote->folio }}
                                    </span>
                                </td>

                                <td>
                                    <span class="table-primary">
                                        {{ $quote->contact->firstName }}
                                        {{ $quote->contact->lastName }}
                                    </span>

                                    <span class="table-secondary">
                                        {{
                                            $quote->contact->whatsAppNumber
                                                ?: (
                                                    $quote->contact->phoneNumber
                                                        ?: (
                                                            $quote->contact->email
                                                                ?: 'Sin contacto adicional'
                                                        )
                                                )
                                        }}
                                    </span>
                                </td>

                                <td>
                                    @forelse ($quote->serviceLinks as $serviceLink)
                                        <span class="table-primary">
                                            {{ $serviceLink->service->name }}
                                        </span>

                                        @if (!$loop->last)
                                            <span class="table-secondary">+</span>
                                        @endif
                                    @empty
                                        —
                                    @endforelse
                                </td>

                                <td>
                                    <span
                                        class="status-badge"
                                        data-status="{{ $quote->status->code }}"
                                    >
                                        {{ $quote->status->name }}
                                    </span>
                                </td>

                                <td>
                                    {{ $quote->locationCity ?: '—' }}

                                    @if ($quote->locationState)
                                        <span class="table-secondary">
                                            {{ $quote->locationState }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $quote->createdAt->format('d/m/Y') }}

                                    <span class="table-secondary">
                                        {{ $quote->createdAt->format('H:i') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <footer class="pagination-bar">
                <div class="pagination-bar__meta">
                    Página {{ $quotes->currentPage() }}
                    de {{ $quotes->lastPage() }}
                </div>

                <div class="pagination-actions">
                    <a
                        class="pagination-link {{ $quotes->onFirstPage() ? 'is-disabled' : '' }}"
                        href="{{ $quotes->previousPageUrl() ?: '#' }}"
                    >
                        Anterior
                    </a>

                    <a
                        class="pagination-link {{ $quotes->hasMorePages() ? '' : 'is-disabled' }}"
                        href="{{ $quotes->nextPageUrl() ?: '#' }}"
                    >
                        Siguiente
                    </a>
                </div>
            </footer>
        @endif
    </section>
@endsection
