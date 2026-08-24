@php
    $editing = isset($project);

    $selectedServiceIds = old(
        'serviceIds',
        $editing ? $project->serviceLinks->pluck('serviceId')->all() : []
    );

    $selectedTagIds = old(
        'tagIds',
        $editing ? $project->tagLinks->pluck('tagId')->all() : []
    );
@endphp

@if (session('success'))
    <div class="alert alert--success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert--error">
        <strong>Revisa la información del proyecto.</strong>
        <ul class="form-error-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="project-form-grid">
    <div class="project-form-main">
        <section class="panel-card detail-card">
            <header class="panel-card__header">
                <div>
                    <h2 class="panel-card__title">Información general</h2>
                    <p class="panel-card__subtitle">Nombre, URL y descripción principal.</p>
                </div>
            </header>

            <div class="detail-card__body">
                <div class="form-grid">
                    <div class="form-field form-field--wide">
                        <label class="info-label" for="name">Nombre del proyecto *</label>
                        <input
                            id="name"
                            class="admin-input"
                            name="name"
                            type="text"
                            maxlength="190"
                            value="{{ old('name', $editing ? $project->name : '') }}"
                            required
                        >
                    </div>

                    <div class="form-field form-field--wide">
                        <label class="info-label" for="slug">Slug</label>
                        <input
                            id="slug"
                            class="admin-input"
                            name="slug"
                            type="text"
                            maxlength="190"
                            value="{{ old('slug', $editing ? $project->slug : '') }}"
                            placeholder="Se genera automáticamente si lo dejas vacío"
                        >
                    </div>

                    <div class="form-field form-field--wide">
                        <label class="info-label" for="shortDescription">Descripción corta</label>
                        <textarea
                            id="shortDescription"
                            class="admin-textarea"
                            name="shortDescription"
                            rows="3"
                            maxlength="500"
                        >{{ old('shortDescription', $editing ? $project->shortDescription : '') }}</textarea>
                    </div>

                    <div class="form-field form-field--wide">
                        <label class="info-label" for="description">Descripción</label>
                        <textarea
                            id="description"
                            class="admin-textarea"
                            name="description"
                            rows="6"
                        >{{ old('description', $editing ? $project->description : '') }}</textarea>
                    </div>
                </div>
            </div>
        </section>

        <section class="panel-card detail-card">
            <header class="panel-card__header">
                <div>
                    <h2 class="panel-card__title">Caso de proyecto</h2>
                    <p class="panel-card__subtitle">Problema del cliente y solución aplicada.</p>
                </div>
            </header>

            <div class="detail-card__body">
                <div class="form-field">
                    <label class="info-label" for="challengeDescription">Reto / necesidad</label>
                    <textarea
                        id="challengeDescription"
                        class="admin-textarea"
                        name="challengeDescription"
                        rows="5"
                    >{{ old('challengeDescription', $editing ? $project->challengeDescription : '') }}</textarea>
                </div>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="solutionDescription">Solución</label>
                    <textarea
                        id="solutionDescription"
                        class="admin-textarea"
                        name="solutionDescription"
                        rows="5"
                    >{{ old('solutionDescription', $editing ? $project->solutionDescription : '') }}</textarea>
                </div>
            </div>
        </section>

        <section class="panel-card detail-card">
            <header class="panel-card__header">
                <div>
                    <h2 class="panel-card__title">Clasificación</h2>
                    <p class="panel-card__subtitle">Relaciona el proyecto con servicios y etiquetas.</p>
                </div>
            </header>

            <div class="detail-card__body">
                <div class="classification-grid">
                    <div>
                        <span class="info-label">Servicios</span>
                        <div class="check-list">
                            @foreach ($services as $service)
                                <label class="check-item">
                                    <input
                                        type="checkbox"
                                        name="serviceIds[]"
                                        value="{{ $service->serviceId }}"
                                        @checked(in_array($service->serviceId, $selectedServiceIds, true))
                                    >
                                    <span>{{ $service->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <span class="info-label">Etiquetas</span>
                        <div class="check-list">
                            @foreach ($tags as $tag)
                                <label class="check-item">
                                    <input
                                        type="checkbox"
                                        name="tagIds[]"
                                        value="{{ $tag->tagId }}"
                                        @checked(in_array($tag->tagId, $selectedTagIds, true))
                                    >
                                    <span>{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <aside class="project-form-aside">
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
                        @checked((bool) old('isPublished', $editing ? $project->isPublished : false))
                    >
                    <span>Proyecto publicado</span>
                </label>

                <input type="hidden" name="isFeatured" value="0">
                <label class="toggle-row">
                    <input
                        type="checkbox"
                        name="isFeatured"
                        value="1"
                        @checked((bool) old('isFeatured', $editing ? $project->isFeatured : false))
                    >
                    <span>Proyecto destacado</span>
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
                        value="{{ old('displayOrder', $editing ? $project->displayOrder : 0) }}"
                    >
                </div>
            </div>
        </section>

        <section class="panel-card detail-card">
            <header class="panel-card__header">
                <div>
                    <h2 class="panel-card__title">Ubicación</h2>
                </div>
            </header>

            <div class="detail-card__body">
                <div class="form-field">
                    <label class="info-label" for="locationCity">Ciudad</label>
                    <input
                        id="locationCity"
                        class="admin-input"
                        name="locationCity"
                        type="text"
                        maxlength="120"
                        value="{{ old('locationCity', $editing ? $project->locationCity : '') }}"
                    >
                </div>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="locationState">Estado</label>
                    <input
                        id="locationState"
                        class="admin-input"
                        name="locationState"
                        type="text"
                        maxlength="120"
                        value="{{ old('locationState', $editing ? $project->locationState : '') }}"
                    >
                </div>

                <div class="form-field form-field--spaced">
                    <label class="info-label" for="projectYear">Año</label>
                    <input
                        id="projectYear"
                        class="admin-input"
                        name="projectYear"
                        type="number"
                        min="1900"
                        max="2100"
                        value="{{ old('projectYear', $editing ? $project->projectYear : '') }}"
                    >
                </div>
            </div>
        </section>

        <button class="button button--full project-save-button" type="submit">
            {{ $editing ? 'Guardar cambios' : 'Crear proyecto' }}
        </button>
    </aside>
</div>
