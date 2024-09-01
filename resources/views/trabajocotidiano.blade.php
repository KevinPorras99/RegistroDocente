@extends('sideynavbar')

@section('title', 'Trabajo Cotidiano')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
@endsection

@section('content')
    <div class="content">
        <h1><i class="fas fa-tasks"></i> Trabajo Cotidiano</h1>
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
                            <th>Nombre</th>
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
</script>
@endsection
