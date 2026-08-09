<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Panel | Somos Constructivos</title>
</head>

<body>
    <header>
        <strong>SOMOS CONSTRUCTIVOS</strong>

        <form
            method="POST"
            action="{{ route('admin.logout') }}"
        >
            @csrf

            <button type="submit">
                Cerrar sesión
            </button>
        </form>
    </header>

    <main>
        <h1>Panel administrativo</h1>

        <p>
            Bienvenido,
            {{ auth()->user()->firstName }}.
        </p>

        <h2>Resumen</h2>

        <p>
            Solicitudes nuevas:
            <strong>{{ $newQuoteCount }}</strong>
        </p>

        <p>
            Cotizaciones abiertas:
            <strong>{{ $openQuoteCount }}</strong>
        </p>

        <h2>Cotizaciones recientes</h2>

        @forelse ($recentQuotes as $quote)
            <p>
                <strong>{{ $quote->folio }}</strong>

                —
                {{ $quote->contact->firstName }}

                —
                {{ $quote->status->name }}
            </p>
        @empty
            <p>No hay cotizaciones todavía.</p>
        @endforelse
    </main>
</body>
</html>