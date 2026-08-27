<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Somos Constructivos')</title>
    <meta name="description" content="@yield('metaDescription', 'Soluciones constructivas para proyectos residenciales.')">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<script
    src="{{ asset('js/public-navigation.js') }}"
    defer
></script>
<body>
    <header class="site-header">
        <div class="site-shell site-header__inner">
            <a class="brand" href="/">
                <span class="brand__mark">
                  <img
                        class="brand__mark-image"
                        src="{{ $companyMonogramUrl }}"
                        alt="Monograma {{
                            $companyProfile?->companyName
                            ?: 'Somos Constructivos'
                        }}"
                    >
                </span>
                <span>
                    <strong>SOMOS CONSTRUCTIVOS</strong>
                    <small>{{ $companyProfile?->slogan ?: 'Tu proyecto, nuestro compromiso!' }}</small>
                </span>
            </a>
            <button
                class="site-menu-toggle"
                type="button"
                aria-label="Abrir menú"
                aria-expanded="false"
                aria-controls="siteNavigation"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>
        <nav
                id="siteNavigation"
                class="site-nav"
            >
            <a
                class="{{ request()->routeIs('public.home') ? 'is-active' : '' }}"
                href="{{ route('public.home') }}"
            >
            Inicio
            </a>

            <a
                class="{{ request()->routeIs('public.services.*') ? 'is-active' : '' }}"
                href="{{ route('public.services.index') }}"
            >
            Servicios
            </a>

            <a
                class="{{ request()->routeIs('public.projects.*') ? 'is-active' : '' }}"
                href="{{ route('public.projects.index') }}"
            >
            Proyectos
            </a>

            <a
                class="{{ request()->routeIs('public.quote.*') ? 'is-active' : '' }}"
                href="{{ route('public.quote.create') }}"
            >
            Cotizar
            </a>

            <a
                class="{{ request()->routeIs('public.contact') ? 'is-active' : '' }}"
                href="{{ route('public.contact') }}"
            >
            Contacto
            </a>
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
