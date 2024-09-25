<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación')</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
    <script src="{{ asset('js/sideynavbar.script.js') }}"></script>
    @yield('styles')
</head>
<body>
    <div class="sidebar">
        <div>
            <div class="logo">
                <span>Registro Docente</span>
            </div>
            <a href="{{ route('home') }}"><i class="fas fa-home"></i> <span> Inicio</span></a>
            <a href="{{ route('cursos') }}"><i class="fas fa-book"></i> <span> Cursos</span></a>
            <a href="{{ route('estudiantes') }}"><i class="fas fa-user-graduate"></i> <span> Estudiantes</span></a>
            <a href="{{ route('trabajocotidiano') }}"><i class="fas fa-briefcase"></i> <span> Trabajo Cotidiano</span></a>
            <a href="{{ route('examenes') }}"><i class="fas fa-file-alt"></i> <span> Pruebas o Exámenes</span></a>
            <a href="{{ route('tareasyasignaciones') }}"><i class="fas fa-tasks"></i> <span> Tareas o Asignaciones</span></a>
            <a href="#conducta-desempeno"><i class="fas fa-user-check"></i> <span> Conducta y Desempeño</span></a>
            <a href="#asistencia"><i class="fas fa-calendar-check"></i> <span> Asistencia</span></a>
            <a href="#calificaciones"><i class="fas fa-chart-line"></i> <span> Calificaciones</span></a>
        </div>
        <button class="toggle-btn" onclick="toggleSidebar()">
            <i class="fas fa-arrow-left"></i>
        </button>
    </div>

    <div class="navbar">
        <div class="logo">
            <img src="{{ asset('images/Steam2-removebg.png') }}" alt="Logo" class="logo">
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Buscar..." style="width: 300px;">
            <button><i class="fas fa-search"></i></button>
        </div>
        <div class="user-menu">
            <div class="user-menu img">
                @if(auth()->user()->profile_image)
                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}">
                @else
                    <div class="initials">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) . strtoupper(substr(auth()->user()->surname, 0, 1)) }}
                    </div>
                @endif
            </div>

            <div class="dropdown">
                <button class="dropbtn"></button>
                <div class="dropdown-content">
                    <a href="#perfil" onclick="openProfileModal()">Perfil</a>
                    <a href="#configuracion">Configuración</a>
                    <a href="{{ route('login.destroy') }}">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        @yield('content')
    </div>

    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeProfileModal()">&times;</span>
            <h2>Perfil</h2>
            <div class="container">
                @if(auth()->user()->profile_image)
                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}" class="profile-picture img">
                @else
                    <div class="initials-modal">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) . strtoupper(substr(auth()->user()->surname, 0, 1)) }}
                    </div>
                @endif

                <form action="{{ route('profile.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="profile_image" class="form-control-file mt-3" required>
                    <button type="submit" class="btn">Subir Nueva Foto</button>
                </form>

                <form action="{{ route('profile.delete') }}" method="POST" class="mt-3" onsubmit="return confirmDelete()">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar Foto</button>
                </form>
            </div>
            <div class="profile-info">
                <label for="fullName">Nombre Completo:</label>
                <input type="text" id="fullName" value="{{ auth()->user()->name }} {{ auth()->user()->surname }}" readonly>

                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" value="{{ auth()->user()->email }}" readonly>
                <a href="{{ route('password.request') }}" class="text-gray" style="font-size: 16px;">Cambiar contraseña</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/sideynavbar.script.js') }}"></script>
    @yield('scripts')
</body>
</html>