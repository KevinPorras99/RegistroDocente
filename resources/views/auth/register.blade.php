<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/login.styles.css') }}">
</head>
<body>
    <nav class="navbar navbar-custom">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('images/Steam2-removebg.png') }}" alt="Logo">
        </a>
        <a class="btn btn-primary" href="{{ route('login.index') }}">Iniciar Sesión</a>
    </nav>

    
        
            <div class="register-container">
                <h1>Registrarse</h1>
                <div class="icon-wrapper">
                    <div class="icon-circle">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
                <form method="POST" action="{{ route('register.store') }}">
                    @csrf
                    <div class="form-group">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nombre completo" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Correo Electrónico" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="password-wrapper">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" value="{{ old('password') }}" required>
                            <i class="fas fa-eye" id="togglePassword"></i>
                        </div>
                        <div class="password-requirements" id="passwordRequirements">
                            <ul>
                                <li id="lengthRequirement" class="requirement">Debe tener entre 8 y 32 caracteres.<i class="fas fa-check"></i></li>
                                <li id="uppercaseRequirement" class="requirement">Debe contener al menos una letra mayúscula.<i class="fas fa-check"></i></li>
                                <li id="lowercaseRequirement" class="requirement">Debe contener al menos una letra minúscula.<i class="fas fa-check"></i></li>
                                <li id="numberRequirement" class="requirement">Debe contener al menos un número.<i class="fas fa-check"></i></li>
                                <li id="specialRequirement" class="requirement">Debe contener al menos un carácter especial (@, $, !, %, *, ?, &).<i class="fas fa-check"></i></li>
                            </ul>
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="password-wrapper">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmar contraseña" required>
                            <i class="fas fa-eye" id="togglePasswordConfirmation"></i>
                        </div>
                        <div class="match-requirements" id="matchRequirements">
                            <ul>
                                <li id="matchRequirement" class="invalid">Las contraseñas deben coincidir.</li>
                            </ul>
                        </div>
                        @error('password_confirmation')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-login btn-block" id="registerbutton">Registrarse</button>
                    <div class="mt-4">
                        <span class="ya-tienes">¿Ya tienes una cuenta?</span>
                        <a href="{{ route('login.index') }}" class="font-weight-bold">Iniciar sesión</a>
                    </div>
                </form>
            </div>
        
    

    <footer class="footer-custom">
        <div>Copyright&copy; 2024 TeamSteam</div>
        <div class="social-icons">
            <div class="icon-circle">
                <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
            </div>
            <div class="icon-circle">
                <a href="https://www.twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
            </div>
            <div class="icon-circle">
                <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="privacy-terms">
            <a href="#">Políticas de Privacidad</a>
            <a href="#">Términos de Uso</a>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>



<script src="{{ asset('js/login.script.js') }}"></script>