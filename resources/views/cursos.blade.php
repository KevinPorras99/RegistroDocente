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

       
        <!-- Modal de Agregar Curso -->
        <div class="modal fade" id="addCourseModal" tabindex="-1" role="dialog" aria-labelledby="addCourseModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCourseModalLabel">Agregar Curso</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addCourseForm" action="{{ route('courses.store') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-8 pr-4"> <!-- Aumentar el tamaño de la columna izquierda y añadir padding-right -->
                                    <h5>Datos generales</h5>
                                    <div class="form-group">
                                        <label for="add-name">Nombre</label>
                                        <input type="text" class="form-control" id="add-name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="add-grade">Grado</label>
                                        <input type="text" class="form-control" id="add-grade" name="grade" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="add-institution">Institución</label>
                                        <input type="text" class="form-control" id="add-institution" name="institution" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="add-classroom">Aula</label>
                                        <input type="text" class="form-control" id="add-classroom" name="classroom" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="add-cycle">Ciclo</label>
                                        <select class="form-control" id="add-cycle" name="cycle" required>
                                            <option value="">Seleccione un ciclo</option>
                                            <option value="Trimestre">Trimestre</option>
                                            <option value="Cuatrimestre">Cuatrimestre</option>
                                            <option value="Semestre">Semestre</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="cycleNumberGroup" style="display: none;">
                                        <label for="cycleNumber">Número de Ciclo</label>
                                        <select class="form-control" id="cycleNumber" name="cycle_number" required>
                                            <!-- Las opciones se llenarán dinámicamente -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 pl-4" id="percentagesColumn" style="display: none; border-left: 1px solid #ddd;"> <!-- Reducir el tamaño de la columna derecha y añadir padding-left -->
                                    <h5>Porcentajes</h5>
                                    <div id="percentagesSection">
                                        <div class="form-group">
                                            <label for="dailyWorkPercentage">Porcentaje de Trabajo Cotidiano</label>
                                            <input type="number" class="form-control" id="dailyWorkPercentage" name="daily_work_percentage" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="examPercentage">Porcentaje de Exámenes</label>
                                            <input type="number" class="form-control" id="examPercentage" name="exam_percentage" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="assignmentPercentage">Porcentaje de Asignaciones/Extra-Clase</label>
                                            <input type="number" class="form-control" id="assignmentPercentage" name="assignment_percentage" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="conductPercentage">Porcentaje de Conducta</label>
                                            <input type="number" class="form-control" id="conductPercentage" name="conduct_percentage" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="attendancePercentage">Porcentaje de Asistencia</label>
                                            <input type="number" class="form-control" id="attendancePercentage" name="attendance_percentage" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Agregar Curso</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para ver curso -->
        <div id="viewCourseModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="viewCourseModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewCourseModalLabel">Detalles del Curso</h5>
                        <button type="button" class="close" onclick="closeViewCourseModal()" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-7">
                                <h5>Datos generales</h5>
                                <p><strong>Nombre:</strong> <span id="viewName"></span></p>
                                <p><strong>Grado:</strong> <span id="viewGrade"></span></p>
                                <p><strong>Institución:</strong> <span id="viewInstitution"></span></p>
                                <p><strong>Grupo:</strong> <span id="viewClassroom"></span></p>
                                <p><strong>Ciclo:</strong> <span id="viewCycle"></span></p>
                                <p><strong>Número de Ciclo:</strong> <span id="viewCycleNumber"></span></p>
                            </div>
                            <div class="col-md-1 d-flex align-items-center justify-content-center">
                                <div style="border-left: 1px solid #ccc; height: 100%;"></div>
                            </div>
                            <div class="col-md-4">
                                <h5>Porcentajes</h5>
                                <p><strong>Porcentaje Trabajos Cotidianos:</strong> <span id="viewDailyWorkPercentage"></span>%</p>
                                <p><strong>Porcentaje Pruebas/Examenes:</strong> <span id="viewExamPercentage"></span>%</p>
                                <p><strong>Porcentaje Tareas/Asignaciones:</strong> <span id="viewAssignmentPercentage"></span>%</p>
                                <p><strong>Porcentaje Conducta y Desempeño:</strong> <span id="viewConductPercentage"></span>%</p>
                                <p><strong>Porcentaje Asistencia:</strong> <span id="viewAttendancePercentage"></span>%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Editar Curso -->
        <div class="modal fade" id="editCourseModal" tabindex="-1" role="dialog" aria-labelledby="editCourseModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCourseModalLabel">Editar Curso</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editCourseForm" action="" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-row">
                                <div class="col-md-8 pr-4"> <!-- Aumentar el tamaño de la columna izquierda y añadir padding-right -->
                                    <h5>Datos generales</h5>
                                    <div class="form-group">
                                        <label for="edit-name">Nombre</label>
                                        <input type="text" class="form-control" id="edit-name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-grade">Grado</label>
                                        <input type="text" class="form-control" id="edit-grade" name="grade" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-institution">Institución</label>
                                        <input type="text" class="form-control" id="edit-institution" name="institution" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-classroom">Aula</label>
                                        <input type="text" class="form-control" id="edit-classroom" name="classroom" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-cycle">Ciclo</label>
                                        <select class="form-control" id="edit-cycle" name="cycle" required>
                                            <option value="Trimestre">Trimestre</option>
                                            <option value="Cuatrimestre">Cuatrimestre</option>
                                            <option value="Semestre">Semestre</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="editCycleNumberGroup">
                                        <label for="edit-cycleNumber">Número de Ciclo</label>
                                        <select class="form-control" id="edit-cycleNumber" name="cycle_number" required>
                                            <!-- Las opciones se llenarán dinámicamente -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 pl-4" id="editPercentagesColumn" style="border-left: 1px solid #ddd;"> <!-- Reducir el tamaño de la columna derecha y añadir padding-left -->
                                    <h5>Porcentajes</h5>
                                    <div id="editPercentagesSection">
                                        <div class="form-group">
                                            <label for="edit-dailyWorkPercentage">Porcentaje de Trabajo Cotidiano</label>
                                            <input type="number" class="form-control" id="edit-dailyWorkPercentage" name="daily_work_percentage" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit-examPercentage">Porcentaje de Exámenes</label>
                                            <input type="number" class="form-control" id="edit-examPercentage" name="exam_percentage" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit-assignmentPercentage">Porcentaje de Asignaciones/Extra-Clase</label>
                                            <input type="number" class="form-control" id="edit-assignmentPercentage" name="assignment_percentage" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit-conductPercentage">Porcentaje de Conducta</label>
                                            <input type="number" class="form-control" id="edit-conductPercentage" name="conduct_percentage" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit-attendancePercentage">Porcentaje de Asistencia</label>
                                            <input type="number" class="form-control" id="edit-attendancePercentage" name="attendance_percentage" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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
                        <th>Número de Ciclo</th>
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
                            <td>{{ $course->cycle_number }}</td> <!-- Mostrar el número de ciclo -->
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
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function openAddCourseModal() {
        $('#addCourseModal').modal('show');
    }

    function closeAddCourseModal() {
        $('#addCourseModal').modal('hide');
    }

    function openViewCourseModal(course) {
    document.getElementById('viewName').innerText = course.name;
    document.getElementById('viewGrade').innerText = course.grade;
    document.getElementById('viewInstitution').innerText = course.institution;
    document.getElementById('viewClassroom').innerText = course.classroom;
    document.getElementById('viewCycle').innerText = course.cycle;
    document.getElementById('viewCycleNumber').innerText = course.cycle_number; // Cargar el número de ciclo
    document.getElementById('viewDailyWorkPercentage').innerText = course.daily_work_percentage;
    document.getElementById('viewExamPercentage').innerText = course.exam_percentage;
    document.getElementById('viewAssignmentPercentage').innerText = course.assignment_percentage;
    document.getElementById('viewConductPercentage').innerText = course.conduct_percentage;
    document.getElementById('viewAttendancePercentage').innerText = course.attendance_percentage;

    $('#viewCourseModal').modal('show');
    }

    function closeViewCourseModal() {
        $('#viewCourseModal').modal('hide');
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
        document.getElementById('edit-dailyWorkPercentage').value = course.daily_work_percentage;
        document.getElementById('edit-examPercentage').value = course.exam_percentage;
        document.getElementById('edit-assignmentPercentage').value = course.assignment_percentage;
        document.getElementById('edit-conductPercentage').value = course.conduct_percentage;
        document.getElementById('edit-attendancePercentage').value = course.attendance_percentage;

        // Mostrar las secciones de ciclo y porcentajes
        updateCycleOptions(course.cycle, 'edit-cycleNumber', 'editCycleNumberGroup', 'editPercentagesSection');
        $('#editCourseModal').modal('show');
    }

    function closeEditCourseModal() {
        $('#editCourseModal').modal('hide');
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

        $('#assignStudentsModal').modal('show');
    }

    function closeAssignStudentsModal() {
        $('#assignStudentsModal').modal('hide');
    }

    function submitAssignStudentsForm() {
        document.getElementById('assignStudentsForm').submit();
    }

    function updateCycleOptions(cycleType, cycleNumberSelectId, cycleNumberGroupId, percentagesSectionId) {
        const cycleNumberGroup = document.getElementById(cycleNumberGroupId);
        const cycleNumberSelect = document.getElementById(cycleNumberSelectId);
        const percentagesSection = document.getElementById(percentagesSectionId);
        const percentagesColumn = document.getElementById('percentagesColumn');

        cycleNumberSelect.innerHTML = ''; // Limpiar las opciones anteriores

        if (cycleType) {
            let options = [];
            if (cycleType === 'Trimestre') {
                options = ['Primer Trimestre', 'Segundo Trimestre', 'Tercer Trimestre'];
            } else if (cycleType === 'Cuatrimestre') {
                options = ['Primer Cuatrimestre', 'Segundo Cuatrimestre', 'Tercer Cuatrimestre', 'Cuarto Cuatrimestre'];
            } else if (cycleType === 'Semestre') {
                options = ['Primer Semestre', 'Segundo Semestre'];
            }

            options.forEach(option => {
                const opt = document.createElement('option');
                opt.value = option;
                opt.textContent = option;
                cycleNumberSelect.appendChild(opt);
            });

            cycleNumberGroup.style.display = 'block';
            percentagesSection.style.display = 'block';
            percentagesColumn.style.display = 'block';
        } else {
            cycleNumberGroup.style.display = 'none';
            percentagesSection.style.display = 'none';
            percentagesColumn.style.display = 'none';
        }
    }

    document.getElementById('add-cycle').addEventListener('change', function() {
        updateCycleOptions(this.value, 'cycleNumber', 'cycleNumberGroup', 'percentagesSection');
    });

    document.getElementById('cycleNumber').addEventListener('change', function() {
        const percentagesSection = document.getElementById('percentagesSection');
        if (this.value) {
            percentagesSection.style.display = 'block';
        } else {
            percentagesSection.style.display = 'none';
        }
    });

    document.getElementById('edit-cycle').addEventListener('change', function() {
        updateCycleOptions(this.value, 'edit-cycleNumber', 'editCycleNumberGroup', 'editPercentagesSection');
    });

    document.getElementById('edit-cycleNumber').addEventListener('change', function() {
        const percentagesSection = document.getElementById('editPercentagesSection');
        if (this.value) {
            percentagesSection.style.display = 'block';
            // Limpiar los campos de porcentaje
            document.getElementById('edit-dailyWorkPercentage').value = '';
            document.getElementById('edit-examPercentage').value = '';
            document.getElementById('edit-assignmentPercentage').value = '';
            document.getElementById('edit-conductPercentage').value = '';
            document.getElementById('edit-attendancePercentage').value = '';
        } else {
            percentagesSection.style.display = 'none';
        }
    });

    function validatePercentages(formId) {
        const form = document.getElementById(formId);
        const dailyWorkPercentage = parseInt(form.querySelector('[name="daily_work_percentage"]').value) || 0;
        const examPercentage = parseInt(form.querySelector('[name="exam_percentage"]').value) || 0;
        const assignmentPercentage = parseInt(form.querySelector('[name="assignment_percentage"]').value) || 0;
        const conductPercentage = parseInt(form.querySelector('[name="conduct_percentage"]').value) || 0;
        const attendancePercentage = parseInt(form.querySelector('[name="attendance_percentage"]').value) || 0;

        const totalPercentage = dailyWorkPercentage + examPercentage + assignmentPercentage + conductPercentage + attendancePercentage;

        if (totalPercentage !== 100) {
            alert('La suma de los porcentajes debe ser 100% para cada ciclo.');
            return false;
        }
        return true;
    }

    document.getElementById('addCourseForm').addEventListener('submit', function(event) {
        if (!validatePercentages('addCourseForm')) {
            event.preventDefault();
        }
    });

    document.getElementById('editCourseForm').addEventListener('submit', function(event) {
        if (!validatePercentages('editCourseForm')) {
            event.preventDefault();
        }
    });
</script>
@endsection