@extends('sideynavbar')

@section('title', 'Trabajo Cotidiano')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
@endsection

@section('content')
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0"><i class="fas fa-briefcase"></i> Trabajo Cotidiano</h1>
            <form id="filterForm" action="{{ route('dailyWorks.index') }}" method="GET" class="d-inline-block">
                <h2><i class="fas fa-filter"></i>Filtros</h2>
                <select id="courseSelect" name="course" class="form-control d-inline-block" style="width: 300px;"
                    onchange="this.form.submit(); updateCycleOptions();">
                    <option value="">Seleccione un curso</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" data-cycles="{{ $course->cycle }}"
                            {{ request('course') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }} - {{ $course->grade }} - {{ $course->institution }} -
                            {{ $course->classroom }}
                        </option>
                    @endforeach
                </select>
                <select id="cycleSelect" name="cycle" class="form-control d-inline-block ml-2" style="width: 150px;"
                    onchange="this.form.submit();">
                    <option value="">Seleccione un ciclo</option>
                    @if (request('course'))
                        @php
                            $selectedCourse = $courses->firstWhere('id', request('course'));
                            $cycles = $selectedCourse ? explode(',', $selectedCourse->cycle) : [];
                        @endphp
                        @foreach ($cycles as $cycle)
                            <option value="{{ $cycle }}" {{ request('cycle') == $cycle ? 'selected' : '' }}>
                                {{ $cycle }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </form>
        </div>

        <p>Selecciona alguna palabra clave para poner en la barra de búsqueda y presiona Enter</p>

        <!-- Barra de búsqueda y botón para agregar trabajo cotidiano -->
        <div class="d-flex flex-wrap mb-3">
            <form action="{{ route('dailyWorks.index') }}" method="GET" class="mr-2 flex-grow-1 d-flex">
                <input type="text" name="search" placeholder="Buscar trabajo cotidiano..." class="form-control"
                    style="width: 60%;">
                <button type="submit" class="btn btn-primary ml-2 mt-md-0">Buscar</button>
                <button type="submit" class="btn btn-primary ml-2 mt-md-0">Borrar Filtros</button>
            </form>
            <button class="btn btn-primary ml-auto mt-2 mt-md-0" onclick="openAddDailyWorkModal()">
                <i class="fas fa-plus"></i> Agregar trabajo cotidiano
            </button>
        </div>

        <!-- Modal para agregar trabajo cotidiano -->
        <div id="addDailyWorkModal" class="modal" style="display: {{ $errors->any() ? 'block' : 'none' }};">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Trabajo Cotidiano</h5>
                        <button type="button" class="close" onclick="closeAddDailyWorkModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="addDailyWorkForm" action="{{ route('dailyWorks.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="add-name" name="name" value="{{ old('name') }}" required>
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <textarea class="form-control" id="add-description" name="description" required>{{ old('description') }}</textarea>
                                @if ($errors->has('description'))
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="due_date">Fecha de Entrega</label>
                                <input type="date" class="form-control" id="add-due_date" name="due_date" value="{{ old('due_date') }}" required>
                                @if ($errors->has('due_date'))
                                    <span class="text-danger">{{ $errors->first('due_date') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="course">Curso</label>
                                <select id="add-course" name="course_id" class="form-control" required>
                                    <option value="">Seleccione un curso</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" data-cycles="{{ $course->cycle }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('course_id'))
                                    <span class="text-danger">{{ $errors->first('course_id') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="cycle">Ciclo</label>
                                <select id="add-cycle" name="cycle" class="form-control" required>
                                    <option value="">Seleccione un ciclo</option>
                                </select>
                                @if ($errors->has('cycle'))
                                    <span class="text-danger">{{ $errors->first('cycle') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="percentage">Porcentaje</label>
                                <small id="totalPercentageInfo" class="form-text text-muted"></small> <!-- Aquí se mostrará el porcentaje total permitido -->
                                <small id="percentageInfo" class="form-text text-muted"></small>
                                <input type="number" class="form-control" id="percentage" name="percentage" value="{{ old('percentage') }}" required min="0">
                                <small id="allowedPercentageInfo" class="form-text text-muted"></small>
                                @if ($errors->has('percentage'))
                                    <span class="text-danger">{{ $errors->first('percentage') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="file">Archivo</label>
                                <input type="file" class="form-control" id="add-file" name="file" accept=".pdf,.txt,.doc,.docx">
                            </div>
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para visualizar trabajo cotidiano -->
        <div id="viewDailyWorkModal" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Visualizar Trabajo Cotidiano</h5>
                        <button type="button" class="close" onclick="closeViewDailyWorkModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Nombre:</strong> <span id="viewName"></span></p>
                        <p><strong>Descripción:</strong> <span id="viewDescription"></span></p>
                        <p><strong>Fecha de Entrega:</strong> <span id="viewDueDate"></span></p>
                        <p><strong>Curso:</strong> <span id="viewCourse"></span></p> <!-- Mostrar el nombre del curso -->
                        <p><strong>Ciclo:</strong> <span id="viewCycle"></span></p>
                        <p><strong>Institución:</strong> <span id="viewInstitution"></span></p>
                        <!-- Mostrar la institución -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para editar trabajo cotidiano -->
        <div id="editDailyWorkModal" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Trabajo Cotidiano</h5>
                        <button type="button" class="close" onclick="closeEditDailyWorkModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="editDailyWorkForm" action="" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="edit-name">Nombre</label>
                                <input type="text" id="edit-name" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-description">Descripción</label>
                                <textarea id="edit-description" name="description" class="form-control" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="edit-due_date">Fecha de Entrega</label>
                                <input type="date" id="edit-due_date" name="due_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="edit-course">Curso</label>
                                <select id="edit-course" name="course_id" class="form-control" required>
                                    <!-- Opciones de curso -->
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}" data-cycles="{{ $course->cycle }}">
                                            {{ $course->name }} - {{ $course->grade }} - {{ $course->institution }} - {{ $course->classroom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit-cycle">Ciclo</label>
                                <select id="edit-cycle" name="cycle" class="form-control" required>
                                    <!-- Opciones de ciclo -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit-percentage">Porcentaje</label>
                                <input type="number" id="edit-percentage" name="percentage" class="form-control" required>
                                <span id="edit-percentageInfo"></span>
                                <span id="edit-allowedPercentageInfo"></span>
                            </div>
                            <div class="form-group">
                                <label for="edit-file">Archivo</label>
                                <input type="file" id="edit-file" name="file" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de trabajos cotidianos -->
        <div class="container">
            <h1>Lista de Trabajos Cotidianos</h1>
            @if (session('success'))
                <div class="alert alert-success" id="success-message">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre del Trabajo</th>
                            <th>Curso</th>
                            <th>Fecha de Entrega</th>
                            <th>Ciclo</th>
                            <th>Archivo</th>
                            <th>Valor porcentual</th> <!-- Nueva columna para el porcentaje -->
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dailyWorks as $dailyWork)
                            <tr>
                                <td>{{ $dailyWork->name }}</td>
                                <td>{{ $dailyWork->course->name }}</td>
                                <td>{{ $dailyWork->due_date }}</td>
                                <td>{{ $dailyWork->cycle }}</td>
                                <td>
                                    @if($dailyWork->file_path)
                                    <a href="{{ asset('storage/' . $dailyWork->file_path) }}" target="_blank">Ver Archivo</a>
                                    @else
                                        No hay archivo
                                    @endif
                                </td>
                                <td>{{ $dailyWork->percentage }}%</td> <!-- Mostrar el porcentaje -->
                                <td>
                                    <button class="btn btn-sm" style="background-color: transparent;"
                                        onclick="openViewDailyWorkModal({{ json_encode($dailyWork) }})">
                                        <i class="fas fa-eye" style="color: rgb(80, 125, 252); font-size: 1rem;"></i>
                                    </button>
                                    <button class="btn btn-sm" style="background-color: transparent;"
                                        onclick="openEditDailyWorkModal({{ json_encode($dailyWork) }})">
                                        <i class="fas fa-pencil-alt" style="color: green; font-size: 1rem;"></i>
                                    </button>
                                    <form action="{{ route('dailyWorks.destroy', $dailyWork->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background-color: transparent;"
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar este trabajo cotidiano?');">
                                            <i class="fas fa-trash" style="color: red; font-size: 1rem;"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('dailyWorks.showAddGradesForm', ['courseId' => $dailyWork->course_id, 'cycle' => $dailyWork->cycle]) }}" class="btn btn-sm btn-primary">
                                        Añadir Calificaciones
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">No hay trabajos cotidianos disponibles.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Enlaces de paginación -->
            <div class="d-flex justify-content-center">
                {{ $dailyWorks->links('pagination::bootstrap-4') }}
            </div>
        </div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function openAddDailyWorkModal() {
        document.getElementById('addDailyWorkModal').style.display = 'block';
        updateAddCycleOptions(); // Actualizar las opciones de ciclo cuando se abre el modal
    }

    function closeAddDailyWorkModal() {
        document.getElementById('addDailyWorkModal').style.display = 'none';
    }

    function openViewDailyWorkModal(dailyWork) {
        document.getElementById('viewName').innerText = dailyWork.name;
        document.getElementById('viewDescription').innerText = dailyWork.description;
        document.getElementById('viewDueDate').innerText = dailyWork.due_date;
        document.getElementById('viewCourse').innerText = dailyWork.course.name; // Mostrar el nombre del curso
        document.getElementById('viewCycle').innerText = dailyWork.cycle; // Mostrar ciclo
        document.getElementById('viewInstitution').innerText = dailyWork.course.institution; // Mostrar institución
        document.getElementById('viewDailyWorkModal').style.display = 'block';
    }

    function closeViewDailyWorkModal() {
        document.getElementById('viewDailyWorkModal').style.display = 'none';
    }

    function openEditDailyWorkModal(dailyWork) {
        var formAction = `{{ route('dailyWorks.update', ':id') }}`;
        formAction = formAction.replace(':id', dailyWork.id);
        document.getElementById('editDailyWorkForm').action = formAction;

        document.getElementById('edit-name').value = dailyWork.name;
        document.getElementById('edit-description').value = dailyWork.description;
        document.getElementById('edit-due_date').value = dailyWork.due_date;
        document.getElementById('edit-percentage').value = dailyWork.percentage;

        // Precargar el curso y ciclo
        document.getElementById('edit-course').value = dailyWork.course_id;
        updateEditCycleOptions(dailyWork.course_id, dailyWork.cycle);

        // Limpiar el campo de archivo
        document.getElementById('edit-file').value = '';

        document.getElementById('editDailyWorkModal').style.display = 'block';
    }

    function closeEditDailyWorkModal() {
        document.getElementById('editDailyWorkModal').style.display = 'none';
    }

    // Función para actualizar las opciones de ciclo en el dropdown de filtros
    function createCycleOptions(cycles, selectedCycle, cycleSelectId) {
        const cycleSelect = document.getElementById(cycleSelectId);
        cycleSelect.innerHTML = '<option value="">Seleccione un ciclo</option>';

        if (cycles) {
            const cycleOptions = cycles.split(',');
            cycleOptions.forEach(cycle => {
                const options = [];
                if (cycle.toLowerCase() === 'semestre') {
                    options.push('Primer Semestre', 'Segundo Semestre');
                } else if (cycle.toLowerCase() === 'trimestre') {
                    options.push('Primer Trimestre', 'Segundo Trimestre', 'Tercer Trimestre');
                } else if (cycle.toLowerCase() === 'cuatrimestre') {
                    options.push('Primer Cuatrimestre', 'Segundo Cuatrimestre', 'Tercer Cuatrimestre', 'Cuarto Cuatrimestre');
                } else {
                    options.push(cycle);
                }
                options.forEach(opt => {
                    const option = document.createElement('option');
                    option.value = opt;
                    option.text = opt;
                    if (opt === selectedCycle) option.selected = true;
                    cycleSelect.appendChild(option);
                });
            });
        }
    }

    function updateCycleOptions() {
        const courseSelect = document.getElementById('courseSelect');
        const selectedCourse = courseSelect.options[courseSelect.selectedIndex];
        const cycles = selectedCourse.getAttribute('data-cycles');
        const selectedCycle = "{{ request('cycle') }}";
        createCycleOptions(cycles, selectedCycle, 'cycleSelect');
    }

    function updateAddCycleOptions() {
        const courseSelect = document.getElementById('add-course');
        const selectedCourse = courseSelect.options[courseSelect.selectedIndex];
        const cycles = selectedCourse.getAttribute('data-cycles');
        createCycleOptions(cycles, null, 'add-cycle');
    }

    function updateEditCycleOptions(courseId, selectedCycle) {
        const courseSelect = document.getElementById('edit-course');
        const selectedCourse = courseSelect.querySelector(`option[value="${courseId}"]`);
        const cycles = selectedCourse.getAttribute('data-cycles');
        createCycleOptions(cycles, selectedCycle, 'edit-cycle');
    }

    document.getElementById('edit-course').addEventListener('change', function() {
        updateEditCycleOptions(this.value, null);
        updateEditAllowedPercentage();
    });

    document.getElementById('cycleSelect').addEventListener('change', function() {
        const cycleSelect = document.getElementById('cycleSelect');
        if (cycleSelect.value === 'todos') {
            cycleSelect.value = ''; // Resetear el valor para mostrar todos los registros
        }
        document.getElementById('filterForm').submit();
    });

    document.getElementById('add-course').addEventListener('change', function() {
        updateAddCycleOptions();
        updateAllowedPercentage();
    });

    document.getElementById('add-cycle').addEventListener('change', function() {
        updateAllowedPercentage();
    });

    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('courseSelect').value) {
            updateCycleOptions();
        }
    });

    // Validar la suma de los porcentajes antes de enviar el formulario de agregar curso
    document.getElementById('addCourseForm').addEventListener('submit', function(event) {
        const dailyWorkPercentage = parseInt(document.getElementById('dailyWorkPercentage').value) || 0;
        const examPercentage = parseInt(document.getElementById('examPercentage').value) || 0;
        const assignmentPercentage = parseInt(document.getElementById('assignmentPercentage').value) || 0;
        const conductPercentage = parseInt(document.getElementById('conductPercentage').value) || 0;
        const attendancePercentage = parseInt(document.getElementById('attendancePercentage').value) || 0;

        const totalPercentage = dailyWorkPercentage + examPercentage + assignmentPercentage + conductPercentage + attendancePercentage;

        if (totalPercentage !== 100) {
            event.preventDefault();
            alert('La suma de los porcentajes debe ser 100%.');
        }
    });

    // Validar la suma de los porcentajes antes de enviar el formulario de editar curso
    document.getElementById('editCourseForm').addEventListener('submit', function(event) {
        const dailyWorkPercentage = parseInt(document.getElementById('edit-dailyWorkPercentage').value) || 0;
        const examPercentage = parseInt(document.getElementById('edit-examPercentage').value) || 0;
        const assignmentPercentage = parseInt(document.getElementById('edit-assignmentPercentage').value) || 0;
        const conductPercentage = parseInt(document.getElementById('edit-conductPercentage').value) || 0;
        const attendancePercentage = parseInt(document.getElementById('edit-attendancePercentage').value) || 0;

        const totalPercentage = dailyWorkPercentage + examPercentage + assignmentPercentage + conductPercentage + attendancePercentage;

        if (totalPercentage !== 100) {
            event.preventDefault();
            alert('La suma de los porcentajes debe ser 100%.');
        }
    });

    // Actualizar el porcentaje permitido para trabajos cotidianos
    function updateAllowedPercentage() {
        const courseId = document.getElementById('add-course').value;
        const cycle = document.getElementById('add-cycle').value;
        if (courseId && cycle) {
            fetch(`/courses/${courseId}/allowedPercentage?cycle=${cycle}`)
                .then(response => response.json())
                .then(data => {
                    const allowedPercentage = data.allowedPercentage;
                    const totalPercentage = data.totalPercentage;
                    document.getElementById('percentage').max = allowedPercentage;
                    document.getElementById('percentageInfo').innerText = `Porcentaje disponible: ${allowedPercentage}% de un total de ${totalPercentage}%.`;
                    document.getElementById('allowedPercentageInfo').innerText = `Puedes asignar un porcentaje entre 0 y ${allowedPercentage}.`;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    }

    function updateEditAllowedPercentage() {
        const courseId = document.getElementById('edit-course').value;
        const cycle = document.getElementById('edit-cycle').value;
        if (courseId && cycle) {
            fetch(`/courses/${courseId}/allowedPercentage?cycle=${cycle}`)
                .then(response => response.json())
                .then(data => {
                    const allowedPercentage = data.allowedPercentage;
                    const totalPercentage = data.totalPercentage;
                    document.getElementById('edit-percentage').max = allowedPercentage;
                    document.getElementById('edit-percentageInfo').innerText = `Porcentaje disponible: ${allowedPercentage}% de un total de ${totalPercentage}%.`;
                    document.getElementById('edit-allowedPercentageInfo').innerText = `Puedes asignar un porcentaje entre 0 y ${allowedPercentage}.`;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    }

    document.getElementById('add-course').addEventListener('change', function() {
        updateAddCycleOptions();
        updateAllowedPercentage();
    });

    document.getElementById('add-cycle').addEventListener('change', function() {
        updateAllowedPercentage();
    });

    document.getElementById('addDailyWorkForm').addEventListener('submit', function(event) {
        const percentage = parseInt(document.getElementById('percentage').value) || 0;
        const maxPercentage = parseInt(document.getElementById('percentage').max) || 0;

        if (percentage > maxPercentage) {
            event.preventDefault();
            alert(`El porcentaje no puede exceder el ${maxPercentage}%.`);
        }
    });

    document.getElementById('editDailyWorkForm').addEventListener('submit', function(event) {
        const percentage = parseInt(document.getElementById('edit-percentage').value) || 0;
        const maxPercentage = parseInt(document.getElementById('edit-percentage').max) || 0;

        if (percentage > maxPercentage) {
            event.preventDefault();
            alert(`El porcentaje no puede exceder el ${maxPercentage}%.`);
        }
    });

    // Funciones para manejar el modal de añadir calificaciones
    function openAddGradeModal(studentId) {
        document.getElementById('student_id').value = studentId;
        fetchDailyWorks(studentId);
        document.getElementById('gradeModal').style.display = 'block';
    }

    function closeGradeModal() {
        document.getElementById('gradeModal').style.display = 'none';
    }

    function fetchDailyWorks(studentId) {
        fetch(`/students/${studentId}/dailyWorks`)
            .then(response => response.json())
            .then(data => {
                const dailyWorksContainer = document.getElementById('dailyWorksContainer');
                dailyWorksContainer.innerHTML = '';

                data.dailyWorks.forEach(dailyWork => {
                    const div = document.createElement('div');
                    div.classList.add('form-group');
                    div.innerHTML = `
                        <label for="dailyWork_${dailyWork.id}">${dailyWork.name} (${dailyWork.percentage}%)</label>
                        <input type="number" class="form-control" id="dailyWork_${dailyWork.id}" name="grades[${dailyWork.id}]" min="0" max="100" required>
                    `;
                    dailyWorksContainer.appendChild(div);
                });
            })
            .catch(error => console.error('Error:', error));
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
