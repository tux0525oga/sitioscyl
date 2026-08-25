@php
    $editing = isset($service);
@endphp

@if (session('success'))
    <div class="alert alert--success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert--error">
        <strong>Revisa la información del servicio.</strong>
        <ul class="form-error-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="service-form-grid">
    <div class="service-form-main">
        <section class="panel-card detail-card">
            <header class="panel-card__header">
                <div>
                    <h2 class="panel-card__title">Información general</h2>
                    <p class="panel-card__subtitle">Nombre, URL y descripción del servicio.</p>
                </div>
            </header>

            <div class="detail-card__body">
                <div class="form-field">
                    <label class="info-label" for="name">Nombre del servicio *</label>
                    <input
                        id="name"
                        class="admin-input"
                        name="name"
                        type="text"
                        maxlength="190"
                        value="{{ old('name', $editing ? $service->name : '') }}"
                        required
                    >
                </div>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="slug">Slug</label>
                    <input
                        id="slug"
                        class="admin-input"
                        name="slug"
                        type="text"
                        maxlength="190"
                        value="{{ old('slug', $editing ? $service->slug : '') }}"
                        placeholder="Se genera automáticamente si lo dejas vacío"
                    >
                </div>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="shortDescription">Descripción corta</label>
                    <textarea
                        id="shortDescription"
                        class="admin-textarea"
                        name="shortDescription"
                        rows="3"
                        maxlength="500"
                    >{{ old('shortDescription', $editing ? $service->shortDescription : '') }}</textarea>
                </div>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="description">Descripción completa</label>
                    <textarea
                        id="description"
                        class="admin-textarea"
                        name="description"
                        rows="8"
                    >{{ old('description', $editing ? $service->description : '') }}</textarea>
                </div>
            </div>
        </section>

        <section class="panel-card detail-card">
            <header class="panel-card__header">
                <div>
                    <h2 class="panel-card__title">Encabezado de la página</h2>
                    <p class="panel-card__subtitle">Texto principal que verá el cliente.</p>
                </div>
            </header>

            <div class="detail-card__body">
                <div class="form-field">
                    <label class="info-label" for="heroTitle">Título principal</label>
                    <input
                        id="heroTitle"
                        class="admin-input"
                        name="heroTitle"
                        type="text"
                        maxlength="255"
                        value="{{ old('heroTitle', $editing ? $service->heroTitle : '') }}"
                    >
                </div>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="heroSubtitle">Subtítulo</label>
                    <textarea
                        id="heroSubtitle"
                        class="admin-textarea"
                        name="heroSubtitle"
                        rows="4"
                        maxlength="500"
                    >{{ old('heroSubtitle', $editing ? $service->heroSubtitle : '') }}</textarea>
                </div>
            </div>
        </section>
    </div>

    <aside class="service-form-aside">
        <section class="panel-card detail-card">
            <header class="panel-card__header">
                <div>
                    <h2 class="panel-card__title">Publicación</h2>
                </div>
            </header>

            <div class="detail-card__body">
                <input type="hidden" name="isPublished" value="0">
                <label class="toggle-row">
                    <input
                        type="checkbox"
                        name="isPublished"
                        value="1"
                        @checked((bool) old('isPublished', $editing ? $service->isPublished : false))
                    >
                    <span>Servicio publicado</span>
                </label>

                <input type="hidden" name="isFeatured" value="0">
                <label class="toggle-row">
                    <input
                        type="checkbox"
                        name="isFeatured"
                        value="1"
                        @checked((bool) old('isFeatured', $editing ? $service->isFeatured : false))
                    >
                    <span>Servicio destacado</span>
                </label>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="displayOrder">Orden</label>
                    <input
                        id="displayOrder"
                        class="admin-input"
                        name="displayOrder"
                        type="number"
                        min="0"
                        max="65535"
                        value="{{ old('displayOrder', $editing ? $service->displayOrder : 0) }}"
                    >
                </div>
            </div>
        </section>

        <button class="button button--full" type="submit">
            {{ $editing ? 'Guardar cambios' : 'Crear servicio' }}
        </button>
    </aside>
</div>
