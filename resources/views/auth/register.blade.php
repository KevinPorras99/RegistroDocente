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



<script>
    // Funcionalidad para mostrar u ocultar la contraseña
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");
    const togglePasswordConfirmation = document.querySelector("#togglePasswordConfirmation");
    const passwordConfirmation = document.querySelector("#password_confirmation");
    const passwordRequirements = document.getElementById("passwordRequirements");
    const matchRequirements = document.getElementById("matchRequirements");

    const lengthRequirement = document.getElementById("lengthRequirement");
    const uppercaseRequirement = document.getElementById("uppercaseRequirement");
    const lowercaseRequirement = document.getElementById("lowercaseRequirement");
    const numberRequirement = document.getElementById("numberRequirement");
    const specialRequirement = document.getElementById("specialRequirement");
    const matchRequirement = document.getElementById("matchRequirement");

    // Mostrar las restricciones cuando el usuario se enfoca en el campo de contraseña
    password.addEventListener("focus", function () {
        passwordRequirements.style.display = "block";
    });

    // Ocultar las restricciones cuando el usuario sale del campo de contraseña
    password.addEventListener("blur", function () {
        passwordRequirements.style.display = "none";
    });

    // Mostrar las restricciones cuando el usuario se enfoca en el campo de confirmar contraseña
    passwordConfirmation.addEventListener("focus", function () {
        matchRequirements.style.display = "block";
    });

    // Ocultar las restricciones cuando el usuario sale del campo de confirmar contraseña
    passwordConfirmation.addEventListener("blur", function () {
        matchRequirements.style.display = "none";
    });
    
    togglePassword.addEventListener("mousedown", function (event) {
        event.preventDefault(); // Evita que el campo de contraseña pierda el foco
    });

    togglePasswordConfirmation.addEventListener("mousedown", function (event) {
        event.preventDefault(); // Evita que el campo de contraseña pierda el foco
    });

    registerbutton.addEventListener("mousedown", function (event) {
        event.preventDefault(); // Evita que el campo de contraseña pierda el foco
    });
    

    // Validación en vivo de la contraseña
    password.addEventListener("input", function () {
        const value = password.value;

        // Longitud de la contraseña
        if (value.length >= 8 && value.length <= 32) {
            lengthRequirement.classList.add("valid");
            lengthRequirement.classList.remove("invalid");
        } else {
            lengthRequirement.classList.add("invalid");
            lengthRequirement.classList.remove("valid");
        }

        // Contiene una letra mayúscula
        if (/[A-Z]/.test(value)) {
            uppercaseRequirement.classList.add("valid");
            uppercaseRequirement.classList.remove("invalid");
        } else {
            uppercaseRequirement.classList.add("invalid");
            uppercaseRequirement.classList.remove("valid");
        }

        // Contiene una letra minúscula
        if (/[a-z]/.test(value)) {
            lowercaseRequirement.classList.add("valid");
            lowercaseRequirement.classList.remove("invalid");
        } else {
            lowercaseRequirement.classList.add("invalid");
            lowercaseRequirement.classList.remove("valid");
        }

        // Contiene un número
        if (/[0-9]/.test(value)) {
            numberRequirement.classList.add("valid");
            numberRequirement.classList.remove("invalid");
        } else {
            numberRequirement.classList.add("invalid");
            numberRequirement.classList.remove("valid");
        }

        // Contiene un carácter especial
        if (/[@$!%*?&]/.test(value)) {
            specialRequirement.classList.add("valid");
            specialRequirement.classList.remove("invalid");
        } else {
            specialRequirement.classList.add("invalid");
            specialRequirement.classList.remove("valid");
        }

        // Validar coincidencia de contraseñas
        if (password.value === passwordConfirmation.value && password.value !== "") {
            matchRequirement.classList.add("valid");
            matchRequirement.classList.remove("invalid");
        } else {
            matchRequirement.classList.add("invalid");
            matchRequirement.classList.remove("valid");
        }
    });

    // Validación en vivo de la coincidencia de contraseñas
    passwordConfirmation.addEventListener("input", function () {
        if (password.value === passwordConfirmation.value && password.value !== "") {
            matchRequirement.classList.add("valid");
            matchRequirement.classList.remove("invalid");
        } else {
            matchRequirement.classList.add("invalid");
            matchRequirement.classList.remove("valid");
        }
    });

    // Mostrar/Ocultar contraseña
    togglePassword.addEventListener("click", function () {
        const type = password.getAttribute("type") === "password" ? "text" : "password";
        password.setAttribute("type", type);
        this.classList.toggle("fa-eye-slash");
    });

    // Mostrar/Ocultar confirmar contraseña
    togglePasswordConfirmation.addEventListener("click", function () {
        const type = passwordConfirmation.getAttribute("type") === "password" ? "text" : "password";
        passwordConfirmation.setAttribute("type", type);
        this.classList.toggle("fa-eye-slash");
    });

    document.addEventListener('DOMContentLoaded', function () {
const elements = document.querySelectorAll('input, select, textarea');
elements.forEach(function (element) {
    element.addEventListener('invalid', function (e) {
        // Restablecer el mensaje de error por defecto del navegador
        e.target.setCustomValidity('');

        if (!e.target.validity.valid) {
            switch (e.target.id) {
                case 'email':
                    e.target.setCustomValidity('Por favor, introduce una dirección de correo electrónico válida.');
                    break;
                case 'password':
                    if (e.target.validity.tooShort) {
                        e.target.setCustomValidity('El campo contraseña debe tener al menos 8 caracteres.');
                    } else if (e.target.validity.patternMismatch) {
                        e.target.setCustomValidity('La contraseña debe cumplir con los requisitos especificados.');
                    } else {
                        e.target.setCustomValidity('Por favor, introduce una contraseña válida.');
                    }
                    break;
                case 'password_confirmation':
                    e.target.setCustomValidity('Las contraseñas deben coincidir.');
                    break;
                case 'name':
                case 'text':
                    e.target.setCustomValidity('Este campo es obligatorio.');
                    break;
                default:
                    e.target.setCustomValidity('Este campo es obligatorio.');
                    break;
            }
        }
    });

    element.addEventListener('input', function (e) {
        // Restablecer el mensaje personalizado para permitir que el navegador muestre el mensaje predeterminado si es necesario
        e.target.setCustomValidity('');
    });
});
});
</script>