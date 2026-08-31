@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('pageTitle', 'Panel general')
@section('content')
    <section class="page-heading">
        <div>
            <h1>Resumen comercial</h1>
            <p>Seguimiento general de solicitudes y cotizaciones de Somos Constructivos.</p>
        </div>
    </section>
    <section class="metrics-grid">
        <article class="metric-card">
            <span class="metric-card__label">Solicitudes nuevas</span>
            <strong class="metric-card__value">{{ $newQuoteCount }}</strong>
            <span class="metric-card__hint">Pendientes de primer contacto</span>
        </article>
        <article class="metric-card">
            <span class="metric-card__label">Cotizaciones abiertas</span>
            <strong class="metric-card__value">{{ $openQuoteCount }}</strong>
            <span class="metric-card__hint">En proceso comercial</span>
        </article>
        <article class="metric-card">
            <span class="metric-card__label">Cotizaciones enviadas</span>
            <strong class="metric-card__value">{{ $sentQuoteCount }}</strong>
            <span class="metric-card__hint">Enviadas al cliente</span>
        </article>
    </section>
    <section class="panel-card">
        <header class="panel-card__header">
            <div>
                <h2 class="panel-card__title">Cotizaciones recientes</h2>
                <p class="panel-card__subtitle">Últimos movimientos registrados en el sistema.</p>
            </div>
            <a class="text-link" href="{{ route('admin.quotes.index') }}">Ver todas</a>
        </header>
        @if ($recentQuotes->isEmpty())
            <div class="empty-state">Todavía no hay solicitudes de cotización.</div>
        @else
            <div class="table-wrap">
                <table class="admin-table">
                    <thead><tr><th>Folio</th><th>Cliente</th><th>Estado</th><th>Ubicación</th><th>Fecha</th></tr></thead>
                    <tbody>
                        @foreach ($recentQuotes as $quote)
                            <tr>
                                <td><span class="table-primary">
                                    <a
                                        class="text-link table-primary"
                                        href="{{ route('admin.quotes.show', $quote) }}"
                                    >
                                    {{ $quote->folio }}
                                    </a></span></td>
                                <td>
                                    <span class="table-primary">{{ $quote->contact->firstName }} {{ $quote->contact->lastName }}</span>
                                    @if ($quote->contact->email)<span class="table-secondary">{{ $quote->contact->email }}</span>@endif
                                </td>
                                <td><span class="status-badge" data-status="{{ $quote->status->code }}">{{ $quote->status->name }}</span></td>
                                <td>
                                    {{ $quote->locationCity ?: '—' }}
                                    @if ($quote->locationState)<span class="table-secondary">{{ $quote->locationState }}</span>@endif
                                </td>
                                <td>{{ $quote->createdAt->format('d/m/Y') }}<span class="table-secondary">{{ $quote->createdAt->format('H:i') }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
