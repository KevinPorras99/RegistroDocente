@extends('sideynavbar')

@section('title', 'Trabajo Cotidiano')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
@endsection

@section('content')
    <div class="content">
        <h1>
            <i class="fas fa-briefcase"></i> Trabajo Cotidiano
            <div class="d-inline-block ml-3">
                <select id="courseSelect" class="form-control d-inline-block" style="width: 300px;" onchange="updateCycleOptions()">
                    <option value="">Seleccione un curso</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" data-cycles="{{ $course->cycle }}">
                            {{ $course->name }} - {{ $course->grade }} - {{ $course->institution }} - {{ $course->classroom }}
                        </option>
                    @endforeach
                </select>
                <select id="cycleSelect" class="form-control d-inline-block ml-2" style="width: 150px;">
                    <option value="">Seleccione un ciclo</option>
                </select>
            </div>
        </h1>
        <p>Selecciona alguna palabra clave para poner en la barra de búsqueda y presiona Enter</p>

        <!-- Barra de búsqueda y botón para agregar trabajo cotidiano -->
        <div class="d-flex flex-wrap mb-3">
            <form action="{{ route('dailyWorks.index') }}" method="GET" class="mr-2 flex-grow-1 d-flex">
                <input type="text" name="search" placeholder="Buscar trabajo cotidiano..." class="form-control" style="width: 60%;">
                <button type="submit" class="btn btn-primary ml-2 mt-md-0">Buscar</button>
                <button type="submit" class="btn btn-primary ml-2 mt-md-0">Borrar Filtros</button>
            </form>
            <button class="btn btn-primary ml-auto mt-2 mt-md-0" onclick="openAddDailyWorkModal()">
                <i class="fas fa-plus"></i> Agregar trabajo cotidiano
            </button>
        </div>

        <!-- Modal para agregar trabajo cotidiano -->
        <div id="addDailyWorkModal" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Trabajo Cotidiano</h5>
                        <button type="button" class="close" onclick="closeAddDailyWorkModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('dailyWorks.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="add-name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <textarea class="form-control" id="add-description" name="description" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="due_date">Fecha de Entrega</label>
                                <input type="date" class="form-control" id="add-due_date" name="due_date" required>
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
                        <form id="editDailyWorkForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="edit-name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <textarea class="form-control" id="edit-description" name="description" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="due_date">Fecha de Entrega</label>
                                <input type="date" class="form-control" id="edit-due_date" name="due_date" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de trabajos cotidianos -->
        <div class="container">
            <h1>Lista de Trabajos Cotidianos</h1>
            @if(session('success'))
                <div class="alert alert-success" id="success-message">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre del Trabajo</th>
                            <th>Descripción</th>
                            <th>Fecha de Entrega</th>
                            <th>Archivo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dailyWorks as $dailyWork)
                            <tr>
                                <td>{{ $dailyWork->name }}</td>
                                <td>{{ $dailyWork->description }}</td>
                                <td>{{ $dailyWork->due_date }}</td>
                                <td>
                                    @if($dailyWork->file_path)
                                        <a href="{{ asset('storage/' . $dailyWork->file_path) }}" target="_blank">Ver Archivo</a>
                                    @else
                                        No hay archivo
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm" style="background-color: transparent;" onclick="openViewDailyWorkModal({{ json_encode($dailyWork) }})">
                                        <i class="fas fa-eye" style="color: rgb(80, 125, 252); font-size: 1rem;"></i>
                                    </button>
                                    <button class="btn btn-sm" style="background-color: transparent;" onclick="openEditDailyWorkModal({{ json_encode($dailyWork) }})">
                                        <i class="fas fa-pencil-alt" style="color: green; font-size: 1rem;"></i>
                                    </button>
                                    <form action="{{ route('dailyWorks.destroy', $dailyWork->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background-color: transparent;" onclick="return confirm('¿Estás seguro de que deseas eliminar este trabajo cotidiano?');">
                                            <i class="fas fa-trash" style="color: red; font-size: 1rem;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No hay trabajos cotidianos registrados.</td>
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
    }

    function closeAddDailyWorkModal() {
        document.getElementById('addDailyWorkModal').style.display = 'none';
    }

    function openViewDailyWorkModal(dailyWork) {
        document.getElementById('viewName').innerText = dailyWork.name;
        document.getElementById('viewDescription').innerText = dailyWork.description;
        document.getElementById('viewDueDate').innerText = dailyWork.due_date;
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

        // Limpiar el campo de archivo
        document.getElementById('edit-file').value = '';

        document.getElementById('editDailyWorkModal').style.display = 'block';
    }

    function closeEditDailyWorkModal() {
        document.getElementById('editDailyWorkModal').style.display = 'none';
    }

    function updateCycleOptions() {
    var courseSelect = document.getElementById('courseSelect');
    var cycleSelect = document.getElementById('cycleSelect');
    var selectedCourse = courseSelect.options[courseSelect.selectedIndex];
    var cycles = selectedCourse.getAttribute('data-cycles');

    // Limpiar las opciones del ciclo
    cycleSelect.innerHTML = '<option value="">Seleccione un ciclo</option>';

    // Añadir la opción "Todos"
    var optionTodos = document.createElement('option');
    optionTodos.value = 'Todos';
    optionTodos.text = 'Todos';
    cycleSelect.appendChild(optionTodos);

    if (cycles) {
        var cycleOptions = cycles.split(',');
        cycleOptions.forEach(function(cycle) {
            if (cycle.toLowerCase() === 'semestre') {
                var option1 = document.createElement('option');
                option1.value = 'Primer Semestre';
                option1.text = 'Primer Semestre';
                cycleSelect.appendChild(option1);

                var option2 = document.createElement('option');
                option2.value = 'Segundo Semestre';
                option2.text = 'Segundo Semestre';
                cycleSelect.appendChild(option2);
            } else if (cycle.toLowerCase() === 'trimestre') {
                var option1 = document.createElement('option');
                option1.value = 'Primer Trimestre';
                option1.text = 'Primer Trimestre';
                cycleSelect.appendChild(option1);

                var option2 = document.createElement('option');
                option2.value = 'Segundo Trimestre';
                option2.text = 'Segundo Trimestre';
                cycleSelect.appendChild(option2);

                var option3 = document.createElement('option');
                option3.value = 'Tercer Trimestre';
                option3.text = 'Tercer Trimestre';
                cycleSelect.appendChild(option3);
            } else if (cycle.toLowerCase() === 'cuatrimestre') {
                var option1 = document.createElement('option');
                option1.value = 'Primer Cuatrimestre';
                option1.text = 'Primer Cuatrimestre';
                cycleSelect.appendChild(option1);

                var option2 = document.createElement('option');
                option2.value = 'Segundo Cuatrimestre';
                option2.text = 'Segundo Cuatrimestre';
                cycleSelect.appendChild(option2);

                var option3 = document.createElement('option');
                option3.value = 'Tercer Cuatrimestre';
                option3.text = 'Tercer Cuatrimestre';
                cycleSelect.appendChild(option3);

                var option4 = document.createElement('option');
                option4.value = 'Cuarto Cuatrimestre';
                option4.text = 'Cuarto Cuatrimestre';
                cycleSelect.appendChild(option4);
            } else {
                var option = document.createElement('option');
                option.value = cycle;
                option.text = cycle;
                cycleSelect.appendChild(option);
            }
        });
    }
}
</script>
@endsection
