@extends('sideynavbar')

@section('title', 'Estudiantes')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
@endsection

@section('content')
    <div class="content">
        <h1><i class="fas fa-user-graduate"></i> Estudiantes</h1>

        <!-- Barra de búsqueda y botón para agregar estudiante -->
        <div class="d-flex flex-wrap mb-3">
            <form action="{{ route('students.index') }}" method="GET" class="mr-2 flex-grow-1 d-flex">
                <input type="text" name="search" placeholder="Buscar estudiantes..." class="form-control" style="width: 60%;">
                <button type="submit" class="btn btn-primary ml-2 mt-md-0">Buscar</button>
                <button type="submit" class="btn btn-primary ml-2 mt-md-0">Borrar Filtros</button>
            </form>
            <button class="btn btn-primary ml-auto mt-2 mt-md-0" onclick="openAddStudentModal()">
                <i class="fas fa-plus"></i> Agregar estudiante
            </button>
        </div>

        <!-- Botones para descargar plantilla y cargar archivo -->
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-success" onclick="location.href='{{ route('students.downloadTemplate') }}'">
                Descargar plantilla
            </button>
            <form action="{{ route('students.uploadExcel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="input-group">
                    <input type="file" name="file" class="form-control" required>
                    <button type="submit" class="btn btn-primary ml-2">Cargar archivo</button>
                </div>
            </form>
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
                                <input type="text" class="form-control" id="add-name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="grade">Grado</label>
                                <input type="text" class="form-control" id="add-grade" name="grade" required>
                            </div>
                            <div class="form-group">
                                <label for="institution">Institución</label>
                                <input type="text" class="form-control" id="add-institution" name="institution" required>
                            </div>
                            <div class="form-group">
                                <label for="section">Sección</label>
                                <input type="text" class="form-control" id="add-section" name="section" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal para visualizar estudiante -->
        <div id="viewStudentModal" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Visualizar Estudiante</h5>
                        <button type="button" class="close" onclick="closeViewStudentModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Nombre:</strong> <span id="viewName"></span></p>
                        <p><strong>Grado:</strong> <span id="viewGrade"></span></p>
                        <p><strong>Institución:</strong> <span id="viewInstitution"></span></p>
                        <p><strong>Sección:</strong> <span id="viewSection"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para editar estudiante -->
        <div id="editStudentModal" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Estudiante</h5>
                        <button type="button" class="close" onclick="closeEditStudentModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="editStudentForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="edit-name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="grade">Grado</label>
                                <input type="text" class="form-control" id="edit-grade" name="grade" required>
                            </div>
                            <div class="form-group">
                                <label for="institution">Institución</label>
                                <input type="text" class="form-control" id="edit-institution" name="institution" required>
                            </div>
                            <div class="form-group">
                                <label for="section">Sección</label>
                                <input type="text" class="form-control" id="edit-section" name="section" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
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
            <div class="table-responsive">
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
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->grade }}</td>
                                <td>{{ $student->institution }}</td>
                                <td>{{ $student->section }}</td>
                                <td>
                                    <button class="btn btn-sm" style="background-color: transparent;" onclick="openViewStudentModal({{ json_encode($student) }})">
                                        <i class="fas fa-eye" style="color: rgb(80, 125, 252); font-size: 1rem;"></i>
                                    </button>
                                    <button class="btn btn-sm" style="background-color: transparent;" onclick="openEditStudentModal({{ json_encode($student) }})">
                                        <i class="fas fa-pencil-alt" style="color: green; font-size: 1rem;"></i>
                                    </button>
                                    <a href="{{ route('students.qr', $student->id) }}" class="btn btn-sm" style="background-color: transparent;">
                                        <i class="fas fa-qrcode" style="color: black; font-size: 1rem;"></i>
                                    </a>
                                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background-color: transparent;" onclick="return confirm('¿Estás seguro de que deseas eliminar este estudiante?');">
                                            <i class="fas fa-trash" style="color: red; font-size: 1rem;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No hay estudiantes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Enlaces de paginación -->
        <div class="d-flex justify-content-center">
            {{ $students->links('pagination::bootstrap-4') }}
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

        function openViewStudentModal(student) {
            document.getElementById('viewName').innerText = student.name;
            document.getElementById('viewGrade').innerText = student.grade;
            document.getElementById('viewInstitution').innerText = student.institution;
            document.getElementById('viewSection').innerText = student.section;
            document.getElementById('viewStudentModal').style.display = 'block';
        }

        function closeViewStudentModal() {
            document.getElementById('viewStudentModal').style.display = 'none';
        }

        function openEditStudentModal(student) {
            var formAction = `{{ route('students.update', ':id') }}`;
            formAction = formAction.replace(':id', student.id);
            document.getElementById('editStudentForm').action = formAction;

            document.getElementById('edit-name').value = student.name;
            document.getElementById('edit-grade').value = student.grade;
            document.getElementById('edit-institution').value = student.institution;
            document.getElementById('edit-section').value = student.section;

            document.getElementById('editStudentModal').style.display = 'block';
        }

        function closeEditStudentModal() {
            document.getElementById('editStudentModal').style.display = 'none';
        }

        // Ocultar el mensaje de éxito después de 3 segundos
        setTimeout(function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 3000);
    </script>
@endsection