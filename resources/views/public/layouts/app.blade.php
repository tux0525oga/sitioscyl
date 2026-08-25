<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Somos Constructivos')</title>
    <meta name="description" content="@yield('metaDescription', 'Soluciones constructivas para proyectos residenciales.')">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="site-shell site-header__inner">
            <a class="brand" href="/">
                <span class="brand__mark">
                    <img
                        class="brand__mark-image"
                        src="{{ asset('images/brand/sc-monograma.png') }}"
                        alt="Monograma Somos Constructivos"
                    >
                </span>
                <span>
                    <strong>SOMOS CONSTRUCTIVOS</strong>
                    <small>{{ $companyProfile?->slogan ?: 'Tu proyecto, nuestro compromiso!' }}</small>
                </span>
            </a>
            <nav class="site-nav">
                <a href="#" aria-disabled="true">Inicio</a>
                <a class="{{ request()->routeIs('public.services.*') ? 'is-active' : '' }}" href="{{ route('public.services.index') }}">Servicios</a>
                <a href="#" aria-disabled="true"><a
                                                    class="{{ request()->routeIs('public.projects.*') ? 'is-active' : '' }}"
                                                    href="{{ route('public.projects.index') }}"
                                                    >
                                                    Proyectos
                                                </a></a>
                <a href="#" aria-disabled="true"><a
                                                    class="{{ request()->routeIs('public.quote.*') ? 'is-active' : '' }}"
                                                    href="{{ route('public.quote.create') }}"
                                                    >
                                                    Cotizar
                                                </a></a>
                <a href="#" aria-disabled="true">Contacto</a>
            </nav>
        </div>
    </header>
    <main>@yield('content')</main>
    <footer class="site-footer">
        <div class="site-shell">
            <strong>SOMOS CONSTRUCTIVOS</strong>
            <p>{{ $companyProfile?->slogan ?: 'Tu proyecto, nuestro compromiso!' }}</p>
        </div>
    </footer>
</body>
</html>
