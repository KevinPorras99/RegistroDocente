<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/login.styles.css') }}">
</head>
<body>
    <nav class="navbar navbar-custom">
        <a class="navbar-brand" href="{{ route('login.index') }}">
            <img src="{{ asset('images/Steam2-removebg.png') }}" alt="Logo">
        </a>
        <a class="btn btn-primary" href="{{ route('login.index') }}">Iniciar Sesión</a>
    </nav>

    
    
    <div class="register-container">
        

                <div class="centered-form">
                    <h1>Cambiar Contraseña</h1>
                    <div class="icon-wrapper">
                        <div class="icon-circle">
                            <i class="fas fa-key"></i>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="password-wrapper">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu nueva contraseña" required>
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
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmar nueva contraseña" required>
                                <i class="fas fa-eye" id="togglePasswordConfirmation"></i>
                            </div>
                            <div class="match-requirements" id="matchRequirements">
                                <ul>
                                    <li id="matchRequirement" class="invalid">Las contraseñas deben coincidir.<i class="fas fa-check"></i></li>
                                </ul>
                            </div>
                            @error('password_confirmation')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-login btn-block" id="submitBtn">Cambiar Contraseña</button>
                    </form>
                
                </div>
        
    </div>
</body>
</html>


<script src="{{ asset('js/login.script.js') }}"></script>