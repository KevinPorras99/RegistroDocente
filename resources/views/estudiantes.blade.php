@extends('sideynavbar')

@section('title', 'Estudiantes')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
@endsection

@section('content')
    <div class="content">
        <h1><i class="fas fa-user-graduate"></i> Estudiantes</h1>
        <p>Selecciona una opción del menú para comenzar.</p>
        
        <!-- Barra de búsqueda y botón para agregar estudiante -->
        <div class="d-flex mb-3">
            <form action="{{ route('students.index') }}" method="GET" class="mr-2">
                <input type="text" name="search" placeholder="Buscar estudiantes..." class="form-control" style="width: 300px;">
            </form>
            <button class="btn btn-primary ml-auto" onclick="openAddStudentModal()">
                <i class="fas fa-plus"></i> Agregar estudiante
            </button>
        </div>

        <!-- Modal para agregar estudiante -->
        <div id="addStudentModal" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Estudiante</h5>
                        <button type="button" class="close" onclick="closeAddStudentModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('students.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="grade">Grado</label>
                                <input type="text" class="form-control" id="grade" name="grade" required>
                            </div>
                            <div class="form-group">
                                <label for="institution">Institución</label>
                                <input type="text" class="form-control" id="institution" name="institution" required>
                            </div>
                            <div class="form-group">
                                <label for="section">Sección</label>
                                <input type="text" class="form-control" id="section" name="section" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de estudiantes -->
        <div class="container">
            <h1>Lista de Estudiantes</h1>
            @if(session('success'))
                <div class="alert alert-success" id="success-message">
                    {{ session('success') }}
                </div>
            @endif
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Grado</th>
                        <th>Institución</th>
                        <th>Sección</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->grade }}</td>
                            <td>{{ $student->institution }}</td>
                            <td>{{ $student->section }}</td>
                            <td>
                                <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm" style="background-color: transparent;">
                                    <i class="fas fa-eye" style="color: rgb(81, 105, 243);"></i>
                                </a>
                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm" style="background-color: transparent;">
                                    <i class="fas fa-pencil-alt" style="color: green;"></i>
                                </a>
                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background-color: transparent;" onclick="return confirm('¿Estás seguro de que deseas eliminar este estudiante?');">
                                        <i class="fas fa-trash" style="color: red;"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function openAddStudentModal() {
            document.getElementById('addStudentModal').style.display = 'block';
        }

        function closeAddStudentModal() {
            document.getElementById('addStudentModal').style.display = 'none';
        }

        // Desaparecer el mensaje de éxito después de 3 segundos
        setTimeout(function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 3000); // 3000 milisegundos = 3 segundos
    </script>
@endsection