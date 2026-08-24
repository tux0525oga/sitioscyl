@extends('admin.layouts.app')

@section('title', $quote->folio)
@section('pageTitle', 'Expediente de cotización')

@section('content')
    <section class="page-heading quote-heading">
        <div>
            <a
                class="back-link"
                href="{{ route('admin.quotes.index') }}"
            >
                ← Volver a cotizaciones
            </a>

            <h1>{{ $quote->folio }}</h1>

            <p>
                Expediente comercial y seguimiento interno
                de la solicitud.
            </p>
        </div>

        <span
            class="status-badge status-badge--large"
            data-status="{{ $quote->status->code }}"
        >
            {{ $quote->status->name }}
        </span>
    </section>

    @if (session('success'))
        <div class="alert alert--success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert--error">
            {{ $errors->first() }}
        </div>
    @endif

    <section class="quote-detail-grid">
        <div class="quote-detail-main">
            <article class="panel-card detail-card">
                <header class="panel-card__header">
                    <div>
                        <h2 class="panel-card__title">
                            Cliente
                        </h2>

                        <p class="panel-card__subtitle">
                            Datos de contacto de la solicitud.
                        </p>
                    </div>
                </header>

                <div class="detail-card__body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nombre</span>

                            <strong class="info-value">
                                {{ $quote->contact->firstName }}
                                {{ $quote->contact->lastName }}
                            </strong>
                        </div>

                        <div class="info-item">
                            <span class="info-label">
                                Contacto preferido
                            </span>

                            <strong class="info-value">
                                {{
                                    $quote->contact
                                        ->preferredContactMethod
                                        ?->name
                                        ?: 'No especificado'
                                }}
                            </strong>
                        </div>

                        <div class="info-item">
                            <span class="info-label">
                                WhatsApp
                            </span>

                            <strong class="info-value">
                                {{
                                    $quote->contact->whatsAppNumber
                                        ?: '—'
                                }}
                            </strong>
                        </div>

                        <div class="info-item">
                            <span class="info-label">
                                Teléfono
                            </span>

                            <strong class="info-value">
                                {{
                                    $quote->contact->phoneNumber
                                        ?: '—'
                                }}
                            </strong>
                        </div>

                        <div class="info-item info-item--wide">
                            <span class="info-label">
                                Correo electrónico
                            </span>

                            <strong class="info-value">
                                {{
                                    $quote->contact->email
                                        ?: '—'
                                }}
                            </strong>
                        </div>
                    </div>
                </div>
            </article>

            <article class="panel-card detail-card">
                <header class="panel-card__header">
                    <div>
                        <h2 class="panel-card__title">
                            Solicitud
                        </h2>

                        <p class="panel-card__subtitle">
                            Alcance y contexto de la cotización.
                        </p>
                    </div>
                </header>

                <div class="detail-card__body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">
                                Fecha
                            </span>

                            <strong class="info-value">
                                {{
                                    $quote->createdAt
                                        ->format('d/m/Y H:i')
                                }}
                            </strong>
                        </div>

                        <div class="info-item">
                            <span class="info-label">
                                Plazo preferido
                            </span>

                            <strong class="info-value">
                                {{
                                    $quote->preferredTimeframe
                                        ?->name
                                        ?: 'No especificado'
                                }}
                            </strong>
                        </div>

                        <div class="info-item">
                            <span class="info-label">
                                Ciudad
                            </span>

                            <strong class="info-value">
                                {{
                                    $quote->locationCity
                                        ?: '—'
                                }}
                            </strong>
                        </div>

                        <div class="info-item">
                            <span class="info-label">
                                Estado
                            </span>

                            <strong class="info-value">
                                {{
                                    $quote->locationState
                                        ?: '—'
                                }}
                            </strong>
                        </div>

                        <div class="info-item info-item--wide">
                            <span class="info-label">
                                Colonia / zona
                            </span>

                            <strong class="info-value">
                                {{
                                    $quote->locationNeighborhood
                                        ?: '—'
                                }}
                            </strong>
                        </div>
                    </div>

                    <div class="detail-section">
                        <span class="info-label">
                            Servicios solicitados
                        </span>

                        <div class="service-chip-list">
                            @forelse (
                                $quote->serviceLinks as $serviceLink
                            )
                                <span class="service-chip">
                                    {{
                                        $serviceLink
                                            ->service
                                            ->name
                                    }}
                                </span>
                            @empty
                                <span class="empty-inline">
                                    Sin servicios asociados.
                                </span>
                            @endforelse
                        </div>
                    </div>

                    <div class="detail-section">
                        <span class="info-label">
                            Descripción
                        </span>

                        <div class="description-box">
                            {{
                                $quote->description
                                    ?: 'Sin descripción.'
                            }}
                        </div>
                    </div>

                    @if ($quote->referenceProject)
                        <div class="detail-section">
                            <span class="info-label">
                                Proyecto de referencia
                            </span>

                            <strong class="info-value">
                                {{
                                    $quote->referenceProject->name
                                }}
                            </strong>
                        </div>
                    @endif
                </div>
            </article>

            <article class="panel-card detail-card">
                <header class="panel-card__header">
                    <div>
                        <h2 class="panel-card__title">
                            Archivos privados
                        </h2>

                        <p class="panel-card__subtitle">
                            Fotografías, planos y documentos
                            enviados con la solicitud.
                        </p>
                    </div>
                </header>

                @if ($quote->files->isEmpty())
                    <div class="empty-state">
                        Esta cotización no tiene archivos adjuntos.
                    </div>
                @else
                    <div class="file-list">
                        @foreach ($quote->files as $file)
                            <div class="file-row">
                                <div>
                                    <strong class="file-name">
                                        {{
                                            $file->originalFileName
                                                ?: $file->fileName
                                        }}
                                    </strong>

                                    <span class="table-secondary">
                                        {{
                                            $file->category
                                                ?->name
                                                ?: 'Sin categoría'
                                        }}
                                        ·
                                        {{
                                            number_format(
                                                $file->fileSize / 1024,
                                                1
                                            )
                                        }}
                                        KB
                                    </span>
                                </div>

                                <div class="file-actions">
                                    <a
                                        class="button button--ghost button--small"
                                        href="{{
                                            route(
                                                'admin.quoteFiles.view',
                                                $file
                                            )
                                        }}"
                                        target="_blank"
                                        rel="noopener"
                                    >
                                        Ver
                                    </a>

                                    <a
                                        class="button button--small"
                                        href="{{
                                            route(
                                                'admin.quoteFiles.download',
                                                $file
                                            )
                                        }}"
                                    >
                                        Descargar
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </article>

            <article class="panel-card detail-card">
                <header class="panel-card__header">
                    <div>
                        <h2 class="panel-card__title">
                            Notas internas
                        </h2>

                        <p class="panel-card__subtitle">
                            Información visible únicamente
                            para el equipo administrativo.
                        </p>
                    </div>
                </header>

                <div class="detail-card__body">
                    <form
                        class="note-form"
                        method="POST"
                        action="{{
                            route(
                                'admin.quotes.notes.store',
                                $quote
                            )
                        }}"
                    >
                        @csrf

                        <label
                            class="info-label"
                            for="noteText"
                        >
                            Nueva nota
                        </label>

                        <textarea
                            id="noteText"
                            class="admin-textarea"
                            name="noteText"
                            rows="4"
                            maxlength="5000"
                            required
                            placeholder="Escribe una nota de seguimiento..."
                        >{{ old('noteText') }}</textarea>

                        <div class="form-actions">
                            <button
                                class="button"
                                type="submit"
                            >
                                Agregar nota
                            </button>
                        </div>
                    </form>

                    <div class="note-list">
                        @forelse (
                            $quote->notes
                                ->sortByDesc('createdAt')
                            as $note
                        )
                            <article class="note-item">
                                <div class="note-item__meta">
                                    <strong>
                                        {{
                                            $note->userAccount
                                                ? (
                                                    trim(
                                                        $note
                                                            ->userAccount
                                                            ->firstName
                                                        . ' '
                                                        . $note
                                                            ->userAccount
                                                            ->lastName
                                                    )
                                                )
                                                : 'Sistema'
                                        }}
                                    </strong>

                                    <span>
                                        {{
                                            $note->createdAt
                                                ->format('d/m/Y H:i')
                                        }}
                                    </span>
                                </div>

                                <p>
                                    {{ $note->noteText }}
                                </p>
                            </article>
                        @empty
                            <div class="empty-inline">
                                No hay notas internas todavía.
                            </div>
                        @endforelse
                    </div>
                </div>
            </article>
        </div>

        <aside class="quote-detail-aside">
            <article class="panel-card detail-card">
                <header class="panel-card__header">
                    <div>
                        <h2 class="panel-card__title">
                            Estado comercial
                        </h2>

                        <p class="panel-card__subtitle">
                            Actualiza la etapa de la oportunidad.
                        </p>
                    </div>
                </header>

                <div class="detail-card__body">
                    <form
                        method="POST"
                        action="{{
                            route(
                                'admin.quotes.status.update',
                                $quote
                            )
                        }}"
                    >
                        @csrf
                        @method('PATCH')

                        <label
                            class="info-label"
                            for="statusCode"
                        >
                            Estado
                        </label>

                        <select
                            id="statusCode"
                            class="admin-select"
                            name="statusCode"
                            required
                        >
                            @foreach ($statuses as $status)
                                <option
                                    value="{{ $status->code }}"
                                    @selected(
                                        $quote->quoteStatusId
                                            === $status->quoteStatusId
                                    )
                                >
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>

                        <button
                            class="button button--full"
                            type="submit"
                        >
                            Guardar estado
                        </button>
                    </form>
                </div>
            </article>

            <article class="panel-card detail-card">
                <header class="panel-card__header">
                    <div>
                        <h2 class="panel-card__title">
                            Historial
                        </h2>

                        <p class="panel-card__subtitle">
                            Trazabilidad de cambios de estado.
                        </p>
                    </div>
                </header>

                <div class="timeline">
                    @forelse (
                        $quote->statusHistory
                            ->sortByDesc('createdAt')
                        as $history
                    )
                        <div class="timeline-item">
                            <span class="timeline-dot"></span>

                            <div>
                                <strong>
                                    {{ $history->status->name }}
                                </strong>

                                <span class="timeline-date">
                                    {{
                                        $history->createdAt
                                            ->format('d/m/Y H:i')
                                    }}
                                </span>

                                <span class="timeline-user">
                                    {{
                                        $history->changedByUser
                                            ? (
                                                trim(
                                                    $history
                                                        ->changedByUser
                                                        ->firstName
                                                    . ' '
                                                    . $history
                                                        ->changedByUser
                                                        ->lastName
                                                )
                                            )
                                            : 'Sistema'
                                    }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            Sin historial disponible.
                        </div>
                    @endforelse
                </div>
            </article>
        </aside>
    </section>
@endsection
