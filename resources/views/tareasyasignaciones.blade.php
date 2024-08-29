@extends('sideynavbar')

@section('title', 'Tareas y asignaciones')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
@endsection

@section('content')
    <div class="content">
        <h1><i class="fas fa-tasks"></i> Tareas y Asignaciones</h1>
        <p>Selecciona alguna palabra clave para poner en la barra de búsqueda y presiona Enter</p>
        
        <!-- Barra de búsqueda y botón para agregar tarea -->
        <div class="d-flex flex-wrap mb-3">
            <form action="{{ route('tasks.index') }}" method="GET" class="mr-2 flex-grow-1 d-flex">
                <input type="text" name="search" placeholder="Buscar tareas..." class="form-control" style="width: 60%;">
                <button type="submit" class="btn btn-primary ml-2 mt-md-0">Buscar</button>
                <button type="submit" class="btn btn-primary ml-2 mt-md-0">Borrar Filtros</button>
            </form>
            <button class="btn btn-primary ml-auto mt-2 mt-md-0" onclick="openAddTaskModal()">
                <i class="fas fa-plus"></i> Agregar tarea
            </button>
        </div>

        <!-- Modal para agregar tarea -->
        <div id="addTaskModal" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Tarea</h5>
                        <button type="button" class="close" onclick="closeAddTaskModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('tasks.store') }}" method="POST">
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
                            <button type="submit" class="btn btn-primary">Agregar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para visualizar tarea -->
        <div id="viewTaskModal" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Visualizar Tarea</h5>
                        <button type="button" class="close" onclick="closeViewTaskModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Nombre:</strong> <span id="viewName"></span></p>
                        <p><strong>Descripción:</strong> <span id="viewDescription"></span></p>
                        <p><strong>Fecha de Entrega:</strong> <span id="viewDueDate"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para editar tarea -->
        <div id="editTaskModal" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Tarea</h5>
                        <button type="button" class="close" onclick="closeEditTaskModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="editTaskForm" method="POST">
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

        <!-- Lista de tareas -->
        <div class="container">
            <h1>Lista de Tareas</h1>
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
                            <th>Descripción</th>
                            <th>Fecha de Entrega</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td>{{ $task->name }}</td>
                                <td>{{ $task->description }}</td>
                                <td>{{ $task->due_date }}</td>
                                <td>
                                    <button class="btn btn-sm" style="background-color: transparent;" onclick="openViewTaskModal({{ json_encode($task) }})">
                                        <i class="fas fa-eye" style="color: rgb(80, 125, 252); font-size: 1rem;"></i>
                                    </button>
                                    <button class="btn btn-sm" style="background-color: transparent;" onclick="openEditTaskModal({{ json_encode($task) }})">
                                        <i class="fas fa-pencil-alt" style="color: green; font-size: 1rem;"></i>
                                    </button>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background-color: transparent;" onclick="return confirm('¿Estás seguro de que deseas eliminar esta tarea?');">
                                            <i class="fas fa-trash" style="color: red; font-size: 1rem;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No hay tareas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Enlaces de paginación -->
            <div class="d-flex justify-content-center">
                {{ $tasks->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function openAddTaskModal() {
        document.getElementById('addTaskModal').style.display = 'block';
    }

    function closeAddTaskModal() {
        document.getElementById('addTaskModal').style.display = 'none';
    }

    function openViewTaskModal(task) {
        document.getElementById('viewName').innerText = task.name;
        document.getElementById('viewDescription').innerText = task.description;
        document.getElementById('viewDueDate').innerText = task.due_date;
        document.getElementById('viewTaskModal').style.display = 'block';
    }

    function closeViewTaskModal() {
        document.getElementById('viewTaskModal').style.display = 'none';
    }

    function openEditTaskModal(task) {
        var formAction = `{{ route('tasks.update', ':id') }}`;
        formAction = formAction.replace(':id', task.id);
        document.getElementById('editTaskForm').action = formAction;

        document.getElementById('edit-name').value = task.name;
        document.getElementById('edit-description').value = task.description;
        document.getElementById('edit-due_date').value = task.due_date;

        document.getElementById('editTaskModal').style.display = 'block';
    }

    function closeEditTaskModal() {
        document.getElementById('editTaskModal').style.display = 'none';
    }
</script>
@endsection