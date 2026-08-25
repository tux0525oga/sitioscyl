<section class="panel-card detail-card service-content-section">
    <header class="panel-card__header">
        <div>
            <h2 class="panel-card__title">
                Soluciones
            </h2>

            <p class="panel-card__subtitle">
                Productos, sistemas o variantes
                específicas dentro del servicio.
            </p>
        </div>
    </header>

    <div class="detail-card__body">
        <form
            method="POST"
            action="{{
                route(
                    'admin.services.solutions.store',
                    $service
                )
            }}"
        >
            @csrf

            <div class="form-grid">
                <div class="form-field">
                    <label class="info-label">
                        Nombre *
                    </label>

                    <input
                        class="admin-input"
                        name="name"
                        type="text"
                        maxlength="190"
                        required
                    >
                </div>

                <div class="form-field">
                    <label class="info-label">
                        Slug
                    </label>

                    <input
                        class="admin-input"
                        name="slug"
                        type="text"
                        maxlength="190"
                    >
                </div>

                <div class="form-field form-field--wide">
                    <label class="info-label">
                        Descripción corta
                    </label>

                    <textarea
                        class="admin-textarea"
                        name="shortDescription"
                        rows="2"
                        maxlength="500"
                    ></textarea>
                </div>

                <div class="form-field form-field--wide">
                    <label class="info-label">
                        Descripción
                    </label>

                    <textarea
                        class="admin-textarea"
                        name="description"
                        rows="4"
                    ></textarea>
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

                <div class="form-field">
                    <span class="info-label">
                        Publicación
                    </span>

                    <input
                        type="hidden"
                        name="isPublished"
                        value="0"
                    >

                    <label class="toggle-row">
                        <input
                            type="checkbox"
                            name="isPublished"
                            value="1"
                        >

                        <span>Publicada</span>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button class="button" type="submit">
                    Agregar solución
                </button>
            </div>
        </form>
    </div>

    @if ($serviceSolutions->isNotEmpty())
        <div class="service-content-list">
            @foreach ($serviceSolutions as $solution)
                <article class="service-content-card">
                    <form
                        method="POST"
                        action="{{
                            route(
                                'admin.services.solutions.update',
                                [
                                    $service,
                                    $solution,
                                ]
                            )
                        }}"
                    >
                        @csrf
                        @method('PUT')

                        <div class="form-grid">
                            <div class="form-field">
                                <label class="info-label">
                                    Nombre
                                </label>

                                <input
                                    class="admin-input"
                                    name="name"
                                    type="text"
                                    maxlength="190"
                                    value="{{ $solution->name }}"
                                    required
                                >
                            </div>

                            <div class="form-field">
                                <label class="info-label">
                                    Slug
                                </label>

                                <input
                                    class="admin-input"
                                    name="slug"
                                    type="text"
                                    maxlength="190"
                                    value="{{ $solution->slug }}"
                                >
                            </div>

                            <div class="form-field form-field--wide">
                                <label class="info-label">
                                    Descripción corta
                                </label>

                                <textarea
                                    class="admin-textarea"
                                    name="shortDescription"
                                    rows="2"
                                    maxlength="500"
                                >{{ $solution->shortDescription }}</textarea>
                            </div>

                            <div class="form-field form-field--wide">
                                <label class="info-label">
                                    Descripción
                                </label>

                                <textarea
                                    class="admin-textarea"
                                    name="description"
                                    rows="3"
                                >{{ $solution->description }}</textarea>
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
                                    value="{{ $solution->displayOrder }}"
                                >
                            </div>

                            <div class="form-field">
                                <span class="info-label">
                                    Publicación
                                </span>

                                <input
                                    type="hidden"
                                    name="isPublished"
                                    value="0"
                                >

                                <label class="toggle-row">
                                    <input
                                        type="checkbox"
                                        name="isPublished"
                                        value="1"
                                        @checked($solution->isPublished)
                                    >

                                    <span>Publicada</span>
                                </label>
                            </div>
                        </div>

                        <div class="service-content-actions">
                            <button
                                class="button button--small"
                                type="submit"
                            >
                                Guardar
                            </button>
                        </div>
                    </form>

                    <form
                        method="POST"
                        action="{{
                            route(
                                'admin.services.solutions.destroy',
                                [
                                    $service,
                                    $solution,
                                ]
                            )
                        }}"
                        onsubmit="return confirm('¿Eliminar esta solución?');"
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
                </article>
            @endforeach
        </div>
    @endif
</section>

<section class="panel-card detail-card service-content-section">
    <header class="panel-card__header">
        <div>
            <h2 class="panel-card__title">
                Beneficios
            </h2>

            <p class="panel-card__subtitle">
                Ventajas que ayudan al cliente
                a entender el valor del servicio.
            </p>
        </div>
    </header>

    <div class="detail-card__body">
        <form
            method="POST"
            action="{{
                route(
                    'admin.services.benefits.store',
                    $service
                )
            }}"
        >
            @csrf

            <div class="form-grid">
                <div class="form-field">
                    <label class="info-label">
                        Título *
                    </label>

                    <input
                        class="admin-input"
                        name="title"
                        type="text"
                        maxlength="190"
                        required
                    >
                </div>

                <div class="form-field">
                    <label class="info-label">
                        Icon key
                    </label>

                    <input
                        class="admin-input"
                        name="iconKey"
                        type="text"
                        maxlength="100"
                        placeholder="Ej. security"
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

                <div class="form-field">
                    <label class="info-label">
                        Orden
                    </label>

                    <input
                        class="admin-input"
                        name="displayOrder"
                        type="number"
                        value="0"
                        min="0"
                        max="65535"
                    >
                </div>

                <div class="form-field">
                    <input
                        type="hidden"
                        name="isPublished"
                        value="0"
                    >

                    <label class="toggle-row">
                        <input
                            type="checkbox"
                            name="isPublished"
                            value="1"
                        >

                        <span>Publicado</span>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button class="button" type="submit">
                    Agregar beneficio
                </button>
            </div>
        </form>
    </div>

    @if ($serviceBenefits->isNotEmpty())
        <div class="service-benefit-grid">
            @foreach ($serviceBenefits as $benefit)
                <article class="service-content-card">
                    <form
                        method="POST"
                        action="{{
                            route(
                                'admin.services.benefits.update',
                                [
                                    $service,
                                    $benefit,
                                ]
                            )
                        }}"
                    >
                        @csrf
                        @method('PUT')

                        <div class="form-field">
                            <label class="info-label">
                                Título
                            </label>

                            <input
                                class="admin-input"
                                name="title"
                                type="text"
                                maxlength="190"
                                value="{{ $benefit->title }}"
                                required
                            >
                        </div>

                        <div class="form-field form-field--spaced">
                            <label class="info-label">
                                Icon key
                            </label>

                            <input
                                class="admin-input"
                                name="iconKey"
                                type="text"
                                maxlength="100"
                                value="{{ $benefit->iconKey }}"
                            >
                        </div>

                        <div class="form-field form-field--spaced">
                            <label class="info-label">
                                Descripción
                            </label>

                            <textarea
                                class="admin-textarea"
                                name="description"
                                rows="3"
                                maxlength="2000"
                            >{{ $benefit->description }}</textarea>
                        </div>

                        <div class="form-grid compact-grid">
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
                                    value="{{ $benefit->displayOrder }}"
                                >
                            </div>

                            <div class="form-field">
                                <input
                                    type="hidden"
                                    name="isPublished"
                                    value="0"
                                >

                                <label class="toggle-row">
                                    <input
                                        type="checkbox"
                                        name="isPublished"
                                        value="1"
                                        @checked($benefit->isPublished)
                                    >

                                    <span>Publicado</span>
                                </label>
                            </div>
                        </div>

                        <div class="service-content-actions">
                            <button
                                class="button button--small"
                                type="submit"
                            >
                                Guardar
                            </button>
                        </div>
                    </form>

                    <form
                        method="POST"
                        action="{{
                            route(
                                'admin.services.benefits.destroy',
                                [
                                    $service,
                                    $benefit,
                                ]
                            )
                        }}"
                        onsubmit="return confirm('¿Eliminar este beneficio?');"
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
                </article>
            @endforeach
        </div>
    @endif
</section>

<section class="panel-card detail-card service-content-section">
    <header class="panel-card__header">
        <div>
            <h2 class="panel-card__title">
                Preguntas frecuentes
            </h2>

            <p class="panel-card__subtitle">
                Respuestas que ayudan a resolver
                dudas antes de solicitar una cotización.
            </p>
        </div>
    </header>

    <div class="detail-card__body">
        <form
            method="POST"
            action="{{
                route(
                    'admin.services.faqs.store',
                    $service
                )
            }}"
        >
            @csrf

            <div class="form-field">
                <label class="info-label">
                    Pregunta *
                </label>

                <input
                    class="admin-input"
                    name="question"
                    type="text"
                    maxlength="500"
                    required
                >
            </div>

            <div class="form-field form-field--spaced">
                <label class="info-label">
                    Respuesta *
                </label>

                <textarea
                    class="admin-textarea"
                    name="answer"
                    rows="4"
                    maxlength="5000"
                    required
                ></textarea>
            </div>

            <div class="form-grid compact-grid">
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

                <div class="form-field">
                    <input
                        type="hidden"
                        name="isPublished"
                        value="0"
                    >

                    <label class="toggle-row">
                        <input
                            type="checkbox"
                            name="isPublished"
                            value="1"
                        >

                        <span>Publicada</span>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button class="button" type="submit">
                    Agregar pregunta
                </button>
            </div>
        </form>
    </div>

    @if ($serviceFaqLinks->isNotEmpty())
        <div class="service-content-list">
            @foreach ($serviceFaqLinks as $faqLink)
                @php
                    $faq = $serviceFaqMap->get(
                        $faqLink->faqId
                    );
                @endphp

                @if ($faq)
                    <article class="service-content-card">
                        <form
                            method="POST"
                            action="{{
                                route(
                                    'admin.services.faqs.update',
                                    [
                                        $service,
                                        $faqLink,
                                    ]
                                )
                            }}"
                        >
                            @csrf
                            @method('PUT')

                            <div class="form-field">
                                <label class="info-label">
                                    Pregunta
                                </label>

                                <input
                                    class="admin-input"
                                    name="question"
                                    type="text"
                                    maxlength="500"
                                    value="{{ $faq->question }}"
                                    required
                                >
                            </div>

                            <div class="form-field form-field--spaced">
                                <label class="info-label">
                                    Respuesta
                                </label>

                                <textarea
                                    class="admin-textarea"
                                    name="answer"
                                    rows="4"
                                    maxlength="5000"
                                    required
                                >{{ $faq->answer }}</textarea>
                            </div>

                            <div class="form-grid compact-grid">
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
                                        value="{{ $faqLink->displayOrder }}"
                                    >
                                </div>

                                <div class="form-field">
                                    <input
                                        type="hidden"
                                        name="isPublished"
                                        value="0"
                                    >

                                    <label class="toggle-row">
                                        <input
                                            type="checkbox"
                                            name="isPublished"
                                            value="1"
                                            @checked($faq->isPublished)
                                        >

                                        <span>Publicada</span>
                                    </label>
                                </div>
                            </div>

                            <div class="service-content-actions">
                                <button
                                    class="button button--small"
                                    type="submit"
                                >
                                    Guardar
                                </button>
                            </div>
                        </form>

                        <form
                            method="POST"
                            action="{{
                                route(
                                    'admin.services.faqs.destroy',
                                    [
                                        $service,
                                        $faqLink,
                                    ]
                                )
                            }}"
                            onsubmit="return confirm('¿Eliminar esta pregunta frecuente?');"
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
                    </article>
                @endif
            @endforeach
        </div>
    @endif
</section>
