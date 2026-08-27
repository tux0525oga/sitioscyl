<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title', 'Administración') | Somos Constructivos</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="admin-brand">
                <p class="admin-brand__mark">SC</p>
                <p class="admin-brand__name">SOMOS<br>CONSTRUCTIVOS</p>
                <p class="admin-brand__slogan">Tu proyecto, nuestro compromiso!</p>
            </div>
            <nav class="admin-nav">
                <p class="admin-nav__label">Administración</p>
                <a class="admin-nav__link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="admin-nav__link {{ request()->routeIs('admin.quotes.*') ? 'is-active' : '' }}" href="{{ route('admin.quotes.index') }}">Cotizaciones</a>
                <p class="admin-nav__label">Contenido</p>
                <span class="admin-nav__link"><a
                                                    class="admin-nav__link {{ request()->routeIs('admin.projects.*') ? 'is-active' : '' }}"
                                                    href="{{ route('admin.projects.index') }}"
                                                >
                                                Proyectos
                                                </a></span>
                <span class="admin-nav__link"><a
                                                    class="admin-nav__link {{ request()->routeIs('admin.services.*') ? 'is-active' : '' }}"
                                                    href="{{ route('admin.services.index') }}"
                                                >
                                                Servicios
                                                </a></span>
                <span class="admin-nav__link">Multimedia</span>
                <span class="admin-nav__link"><a
                                                    class="admin-nav__link {{
                                                    request()->routeIs('admin.configuration.*') ? 'is-active' : '' }}"
                                                    href="{{ route('admin.configuration.edit') }}"
                                                >
                                                Configuración
                                                </a></span>
            </nav>
            <div class="admin-sidebar__footer">
                <div class="admin-user">
                    <span class="admin-user__name">{{ auth()->user()->firstName }} {{ auth()->user()->lastName }}</span>
                    <span class="admin-user__role">Acceso administrativo</span>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="admin-logout" type="submit">Cerrar sesión</button>
                </form>
            </div>
        </aside>
        <main class="admin-main">
            <header class="admin-topbar">
                <div>
                    <p class="admin-topbar__eyebrow">Somos Constructivos</p>
                    <p class="admin-topbar__title">@yield('pageTitle', 'Panel administrativo')</p>
                </div>
                <div class="admin-topbar__meta">Administración interna</div>
            </header>
            <div class="admin-content">@yield('content')</div>
        </main>
    </div>
</body>
</html>
