@extends('sideynavbar')

@section('title', 'Cursos')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
@endsection

@section('content')
    <div class="content">
        <h1><i class="fas fa-book"></i> Cursos</h1>
        <p>Selecciona alguna palabra clave para poner en la barra de búsqueda y presiona Enter</p>

        <!-- Mostrar mensaje de éxito -->
        @if(session('success'))
            <div id="success-message" class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Barra de búsqueda y botón para agregar curso -->
        <div class="d-flex flex-wrap mb-3">
            <form action="{{ route('courses.index') }}" method="GET" class="mr-2 flex-grow-1 d-flex">
                <input type="text" name="search" placeholder="Buscar cursos..." class="form-control" style="width: 60%;">
                <button type="submit" class="btn btn-primary ml-2 mt-md-0">Buscar</button>
                <button type="submit" class="btn btn-primary ml-2 mt-md-0">Borrar Filtros</button>
            </form>
            <button class="btn btn-primary ml-auto mt-2 mt-md-0" onclick="openAddCourseModal()">
                <i class="fas fa-plus"></i> Agregar curso
            </button>
        </div>

        <!-- Modal para agregar curso -->
        <div id="addCourseModal" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Curso</h5>
                        <button type="button" class="close" onclick="closeAddCourseModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('courses.store') }}" method="POST">
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
                                <label for="classroom">Grupo</label>
                                <input type="text" class="form-control" id="add-classroom" name="classroom" required>
                            </div>
                            <div class="form-group">
                                <label for="cycle">Ciclo</label>
                                <select class="form-control" id="add-cycle" name="cycle" required>
                                    <option value="Trimestre">Trimestre</option>
                                    <option value="Cuatrimestre">Cuatrimestre</option>
                                    <option value="Semestre">Semestre</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para ver curso -->
        <div id="viewCourseModal" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalles del Curso</h5>
                        <button type="button" class="close" onclick="closeViewCourseModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Nombre:</strong> <span id="viewName"></span></p>
                        <p><strong>Grado:</strong> <span id="viewGrade"></span></p>
                        <p><strong>Institución:</strong> <span id="viewInstitution"></span></p>
                        <p><strong>Grupo:</strong> <span id="viewClassroom"></span></p>
                        <p><strong>Ciclo:</strong> <span id="viewCycle"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para editar curso -->
        <div id="editCourseModal" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Curso</h5>
                        <button type="button" class="close" onclick="closeEditCourseModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="editCourseForm" method="POST">
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
                                <label for="classroom">Grupo</label>
                                <input type="text" class="form-control" id="edit-classroom" name="classroom" required>
                            </div>
                            <div class="form-group">
                                <label for="cycle">Ciclo</label>
                                <select class="form-control" id="edit-cycle" name="cycle" required>
                                    <option value="Trimestre">Trimestre</option>
                                    <option value="Cuatrimestre">Cuatrimestre</option>
                                    <option value="Semestre">Semestre</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

       <!-- Modal para asignar estudiantes -->
        <div id="assignStudentsModal" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Asignar Estudiantes</h5>
                        <button type="button" class="close" onclick="closeAssignStudentsModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="assignStudentsForm" action="{{ route('courses.assignStudents') }}" method="POST">
                            @csrf
                            <input type="hidden" name="course_id" id="course_id" value="">
                
                            @if($students->isEmpty())
                                <p>Primero debes de registrar estudiantes en la sección "Estudiantes".</p>
                            @else
                                @foreach($students as $student)
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="student_{{ $student->id }}" name="student_ids[]"
                                            value="{{ $student->id }}">
                                        <label class="form-check-label" for="student_{{ $student->id }}">
                                            {{ $student->name }}, {{ $student->institution }}, {{ $student->section }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="submitAssignStudentsForm()">Asignar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de cursos -->
        <div class="container">
            <h1>Lista de Cursos</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Grado</th>
                        <th>Institución</th>
                        <th>Grupo</th>
                        <th>Ciclo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->grade }}</td>
                            <td>{{ $course->institution }}</td>
                            <td>{{ $course->classroom }}</td>
                            <td>{{ $course->cycle }}</td>
                            <td>
                                <button type="button" class="btn btn-primary" onclick="openAssignStudentsModal({{ $course->id }}, {{ json_encode($course->students->pluck('id')->toArray()) }})">
                                    Asignar Estudiantes
                                </button>

                                <button class="btn btn-sm" style="background-color: transparent;" onclick="openViewCourseModal({{ json_encode($course) }})">
                                    <i class="fas fa-eye" style="color: rgb(80, 125, 252); font-size: 1rem;"></i>
                                </button>
                                <button class="btn btn-sm" style="background-color: transparent;" onclick="openEditCourseModal({{ json_encode($course) }})">
                                    <i class="fas fa-pencil-alt" style="color: green; font-size: 1rem;"></i>
                                </button>
                                <form action="{{ route('courses.destroy', $course->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background-color: transparent;" onclick="return confirm('¿Estás seguro de que deseas eliminar este curso?');">
                                        <i class="fas fa-trash" style="color: red; font-size: 1rem;"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No hay cursos disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Enlaces de paginación -->
        <div class="d-flex justify-content-center">
            {{ $courses->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function openAddCourseModal() {
            document.getElementById('addCourseModal').style.display = 'block';
        }

        function closeAddCourseModal() {
            document.getElementById('addCourseModal').style.display = 'none';
        }

        function openViewCourseModal(course) {
            document.getElementById('viewName').innerText = course.name;
            document.getElementById('viewGrade').innerText = course.grade;
            document.getElementById('viewInstitution').innerText = course.institution;
            document.getElementById('viewClassroom').innerText = course.classroom;
            document.getElementById('viewCycle').innerText = course.cycle;

            // Fetch and display assigned students
            fetch(`/courses/${course.id}/students`)
                .then(response => response.json())
                .then(data => {
                    const studentsList = document.getElementById('viewStudents');
                    studentsList.innerHTML = '';
                    data.forEach(student => {
                        const li = document.createElement('li');
                        li.textContent = `${student.name} - ${student.institution} - ${student.section}`;
                        studentsList.appendChild(li);
                    });
                });

            document.getElementById('viewCourseModal').style.display = 'block';
        }

        function closeViewCourseModal() {
            document.getElementById('viewCourseModal').style.display = 'none';
        }

        function openEditCourseModal(course) {
            var formAction = `{{ route('courses.update', ':id') }}`;
            formAction = formAction.replace(':id', course.id);
            document.getElementById('editCourseForm').action = formAction;

            document.getElementById('edit-name').value = course.name;
            document.getElementById('edit-grade').value = course.grade;
            document.getElementById('edit-institution').value = course.institution;
            document.getElementById('edit-classroom').value = course.classroom;
            document.getElementById('edit-cycle').value = course.cycle;

            document.getElementById('editCourseModal').style.display = 'block';
        }

        function closeEditCourseModal() {
            document.getElementById('editCourseModal').style.display = 'none';
        }

        // Ocultar el mensaje de éxito después de 3 segundos
        setTimeout(function() {
            var successMessage = document.getElementById('success-message');
            if (successMessage) {
                successMessage.style.display = 'none';
            }
        }, 3000);

        function openAssignStudentsModal(courseId, assignedStudents) {
            document.getElementById('course_id').value = courseId;

            // Marcar los checkboxes como seleccionados si los estudiantes ya están asignados
            const studentCheckboxes = document.querySelectorAll('#assignStudentsModal .form-check-input');
            studentCheckboxes.forEach(checkbox => {
                checkbox.checked = assignedStudents.includes(parseInt(checkbox.value));
            });

            // Abrir el modal
            document.getElementById('assignStudentsModal').style.display = 'block';
        }

        function closeAssignStudentsModal() {
            document.getElementById('assignStudentsModal').style.display = 'none';
        }

        function submitAssignStudentsForm() {
            document.getElementById('assignStudentsForm').submit();
        }
    </script>
@endsection
