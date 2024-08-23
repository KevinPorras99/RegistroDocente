<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/login.styles.css') }}">
</head>
<body>
    <nav class="navbar navbar-custom">
        <a class="navbar-brand" href="{{ route('login.index') }}">
            <img src="{{ asset('images/Steam2-removebg.png') }}" alt="Logo">
        </a>
        <a class="btn btn-primary" href="{{ route('login.index') }}">Iniciar Sesión</a>
    </nav>

    <div class="container">
        <div class="login-container">
            <div class="centered-form">
                <div class="card">
                    <h1>Restablecer Contraseña</h1>
                    <div class="icon-wrapper">
                        <div class="icon-circle">
                            <i class="fas fa-unlock-alt"></i>
                        </div>
                    </div>
                    <p>Ingresa tu correo institucional para restablecer tu contraseña.</p>
                    @if(session('status'))
                        <div class="alert alert-success" id="success-alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger" id="error-alert">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form id="forgot-password-form" method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Correo institucional" value="{{ old('email') }}" required>
                        </div>
                        <button id="submitBtn" type="submit" class="btn btn-login btn-block">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>



<script src="{{ asset('js/login.script.js') }}"></script>