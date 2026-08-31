<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        Administración | Somos Constructivos
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            background: #e4e4df;
            color: #17212a;
            font-family: Arial, sans-serif;
        }

        .panel {
            width: 100%;
            max-width: 430px;
            overflow: hidden;
            border-radius: 20px;
            background: #ffffff;
            box-shadow:
                0 25px 70px rgba(0, 0, 0, 0.15);
        }

        .brand {
            padding: 32px;
            background: #263746;
            color: white;
        }

        .brand strong {
            letter-spacing: 2px;
        }

        .brand h1 {
            margin: 10px 0 6px;
        }

        .brand p {
            margin: 0;
            opacity: 0.72;
        }

        .form {
            padding: 32px;
        }

        label {
            display: block;
            margin: 18px 0 7px;
            font-size: 13px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 13px;
            border: 1px solid #cfd4d7;
            border-radius: 9px;
            font: inherit;
        }

        button {
            width: 100%;
            margin-top: 24px;
            padding: 14px;
            border: 0;
            border-radius: 9px;
            background: #263746;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .error {
            padding: 12px;
            border-radius: 8px;
            background: #fff1f1;
            color: #9b2c2c;
        }
    </style>
</head>

<body>
    <main class="panel">
        <header class="brand">
            <strong>SOMOS CONSTRUCTIVOS</strong>

            <h1>Panel administrativo</h1>

            <p>
                Tu proyecto, nuestro compromiso!
            </p>
        </header>

        <section class="form">
            <h2>Iniciar sesión</h2>

            @if ($errors->any())
                <div class="error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('admin.login.store') }}"
            >
                @csrf

                <label for="email">
                    Correo electrónico
                </label>

                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    autocomplete="username"
                    required
                    autofocus
                >

                <label for="password">
                    Contraseña
                </label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    required
                >

                <button type="submit">
                    Entrar al panel
                </button>
            </form>
        </section>
    </main>
</body>
</html>