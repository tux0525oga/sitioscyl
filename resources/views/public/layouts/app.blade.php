<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('sc-favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('sc-favicon.png') }}">  

    @php
        $pageTitle = trim(
            $__env->yieldContent(
                'title',
                'Somos Constructivos'
            )
        );

        $pageDescription = trim(
            $__env->yieldContent(
                'metaDescription',
                'Soluciones constructivas para proyectos residenciales.'
            )
        );

        $canonicalUrl = trim(
            $__env->yieldContent(
                'canonicalUrl',
                url()->current()
            )
        );

        $robots = trim(
            $__env->yieldContent(
                'robots',
                'index,follow'
            )
        );

        $ogTitle = trim(
            $__env->yieldContent(
                'ogTitle',
                $pageTitle
            )
        );

        $ogDescription = trim(
            $__env->yieldContent(
                'ogDescription',
                $pageDescription
            )
        );

        $ogUrl = trim(
            $__env->yieldContent(
                'ogUrl',
                $canonicalUrl
            )
        );

        $ogType = trim(
            $__env->yieldContent(
                'ogType',
                'website'
            )
        );

        $ogImage = trim(
            $__env->yieldContent(
                'ogImage',
                ''
            )
        );

        $twitterCard = trim(
            $__env->yieldContent(
                'twitterCard',
                $ogImage !== ''
                    ? 'summary_large_image'
                    : 'summary'
            )
        );
    @endphp

    <title>{{ $pageTitle }}</title>

    <meta
        name="description"
        content="{{ $pageDescription }}"
    >

    <link
        rel="canonical"
        href="{{ $canonicalUrl }}"
    >

    <meta
        name="robots"
        content="{{ $robots }}"
    >

    <meta
        property="og:title"
        content="{{ $ogTitle }}"
    >

    <meta
        property="og:description"
        content="{{ $ogDescription }}"
    >

    <meta
        property="og:type"
        content="{{ $ogType }}"
    >

    <meta
        property="og:url"
        content="{{ $ogUrl }}"
    >

    @if ($ogImage !== '')
        <meta
            property="og:image"
            content="{{ $ogImage }}"
        >
    @endif

    <meta
        name="twitter:card"
        content="{{ $twitterCard }}"
    >

    <meta
        name="twitter:title"
        content="{{ $ogTitle }}"
    >

    <meta
        name="twitter:description"
        content="{{ $ogDescription }}"
    >

    @if ($ogImage !== '')
        <meta
            name="twitter:image"
            content="{{ $ogImage }}"
        >
    @endif

    <link
        rel="stylesheet"
        href="{{ asset('css/site.css') }}?v={{ filemtime(public_path('css/site.css')) }}"
    >
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
    <div class="site-shell site-footer__inner">

        <div class="site-footer__brand">
            <strong>
                {{ $companyProfile?->companyName ?: 'SOMOS CONSTRUCTIVOS' }}
            </strong>

            <p>
                {{
                    $companyProfile?->slogan
                    ?: 'Tu proyecto, nuestro compromiso!'
                }}
            </p>
        </div>

        <div class="site-footer__meta">
            <span>
                {{
                    collect([
                        $companyProfile?->locationCity
                            ?: 'San Mateo Atenco',

                        $companyProfile?->locationState
                            ?: 'Estado de México',
                    ])
                    ->filter()
                    ->join(', ')
                }}
            </span>

            <span>
                © {{ now()->year }}
                {{ $companyProfile?->companyName ?: 'Somos Constructivos' }}.
                Todos los derechos reservados.
            </span>
        </div>

    </div>
</footer>
</body>
</html>
