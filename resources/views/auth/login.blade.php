<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/login.styles.css') }}">
</head>
<body>
    <nav class="navbar navbar-custom">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('images/Steam2-removebg.png') }}" alt="Logo">
        </a>
        <a class="btn btn-primary" href="{{ route('register.index') }}">Registrarse</a>
    </nav>

        <div class="container">
            @if(session('success'))
                <div id="successMessage" class="alert alert-success position-absolute w-50 text-center" style="top: 20px; left: 50%; transform: translateX(-50%); z-index: 1000;">
                    {{ session('success') }}
                </div>
            @endif

            <div class="right-half w-100">
                <div class="col-md-8 col-xl-6 text-center mx-auto">
                    <h1>Registro Docente</h1>
                    <p>¡Bienvenido(a) al sistema de registro estudiantil por excelencia!</p>
                </div>
                <div class="register-container">
                    
                        <div class="icon-wrapper">
                            <div class="icon-circle">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('login.store') }}">
                            @csrf
                            <div class="form-group">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}" required>
                            </div>
                            <div class="form-group password-wrapper">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                                <i class="fas fa-eye" id="togglePassword"></i>
                            </div>
                            <div class="text-center">
                                <a href="{{ route('password.request') }}" class="text-gray" style="font-size: 14px;">¿Olvidaste tu contraseña?</a>
                            </div>
                            <button type="submit" class="btn btn-login btn-block">Iniciar sesión</button>
                            
                            @error('message')
                                <div style="color: red; margin-top: 10px;">
                                    * {{ $message }}
                                </div>
                            @enderror

                            <div class="mt-4">
                                <span class="todavia-no-tienes">¿Todavía no tienes una cuenta?</span>
                                <a href="{{ route('register.index') }}" class="font-weight-bold">Crea una ahora</a>
                            </div>
                        </form>
                    
                </div>
            </div>
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
    //Funcionalidad para mostrar u ocultar la contraseña
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function (e) {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            this.classList.toggle("fa-eye-slash");
        });

        // Código para hacer desaparecer el mensaje de éxito después de 3 segundos
        setTimeout(function() {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.style.transition = 'opacity 0.5s ease';
                successMessage.style.opacity = '0';
                setTimeout(function() {
                    successMessage.remove();
                }, 500); // Esperar a que termine la transición antes de eliminar el elemento
            }
        }, 3000);
    </script>