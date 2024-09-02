@extends('sideynavbar')

@section('title', 'Pruebas o Exámenes')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
@endsection

@section('content')
    <div class="content">
        <h1><i class="fas fa-file-alt"></i> Pruebas o Exámenes</h1>
        <p>Selecciona alguna palabra clave para poner en la barra de búsqueda y presiona Enter</p>

        <!-- Barra de búsqueda y botón para agregar examen -->
        <div class="d-flex flex-wrap mb-3">
            <form action="{{ route('exams.index') }}" method="GET" class="mr-2 flex-grow-1 d-flex">
                <input type="text" name="search" placeholder="Buscar exámenes..." class="form-control" style="width: 60%;">
                <button type="submit" class="btn btn-primary ml-2 mt-md-0">Buscar</button>
                <button type="submit" class="btn btn-primary ml-2 mt-md-0">Borrar Filtros</button>
            </form>
            <button class="btn btn-primary ml-auto mt-2 mt-md-0" onclick="openAddExamModal()">
                <i class="fas fa-plus"></i> Agregar examen
            </button>
        </div>

        <!-- Modal para agregar examen -->
        <div id="addExamModal" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Examen</h5>
                        <button type="button" class="close" onclick="closeAddExamModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('exams.store') }}" method="POST" enctype="multipart/form-data">
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

        <!-- Modal para visualizar examen -->
        <div id="viewExamModal" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Visualizar Examen</h5>
                        <button type="button" class="close" onclick="closeViewExamModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Nombre:</strong> <span id="viewName"></span></p>
                        <p><strong>Descripción:</strong> <span id="viewDescription"></span></p>
                        <p><strong>Fecha de Entrega:</strong> <span id="viewDueDate"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para editar examen -->
        <div id="editExamModal" class="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Examen</h5>
                        <button type="button" class="close" onclick="closeEditExamModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="editExamForm" method="POST" enctype="multipart/form-data">
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
                            <div class="form-group">
                                <label for="file">Archivo</label>
                                <input type="file" class="form-control" id="edit-file" name="file" accept=".pdf,.txt,.doc,.docx">
                            </div>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de exámenes -->
        <div class="container">
            <h1>Lista de Exámenes</h1>
            @if(session('success'))
                <div class="alert alert-success" id="success-message">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre del Examen</th>
                            <th>Descripción</th>
                            <th>Fecha de Entrega</th>
                            <th>Archivo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exams as $exam)
                            <tr>
                                <td>{{ $exam->name }}</td>
                                <td>{{ $exam->description }}</td>
                                <td>{{ $exam->due_date }}</td>
                                <td>
                                    @if($exam->file_path)
                                        <a href="{{ asset('storage/' . $exam->file_path) }}" target="_blank">Ver Archivo</a>
                                    @else
                                        No hay archivo
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm" style="background-color: transparent;" onclick="openViewExamModal({{ json_encode($exam) }})">
                                        <i class="fas fa-eye" style="color: rgb(80, 125, 252); font-size: 1rem;"></i>
                                    </button>
                                    <button class="btn btn-sm" style="background-color: transparent;" onclick="openEditExamModal({{ json_encode($exam) }})">
                                        <i class="fas fa-pencil-alt" style="color: green; font-size: 1rem;"></i>
                                    </button>
                                    <form action="{{ route('exams.destroy', $exam->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="background-color: transparent;" onclick="return confirm('¿Estás seguro de que deseas eliminar este examen?');">
                                            <i class="fas fa-trash" style="color: red; font-size: 1rem;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No hay exámenes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Enlaces de paginación -->
            <div class="d-flex justify-content-center">
                {{ $exams->links('pagination::bootstrap-4') }}
            </div>
        </div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function openAddExamModal() {
        document.getElementById('addExamModal').style.display = 'block';
    }

    function closeAddExamModal() {
        document.getElementById('addExamModal').style.display = 'none';
    }

    function openViewExamModal(exam) {
        document.getElementById('viewName').innerText = exam.name;
        document.getElementById('viewDescription').innerText = exam.description;
        document.getElementById('viewDueDate').innerText = exam.due_date;
        document.getElementById('viewExamModal').style.display = 'block';
    }

    function closeViewExamModal() {
        document.getElementById('viewExamModal').style.display = 'none';
    }

    function openEditExamModal(exam) {
    var formAction = `{{ route('exams.update', ':id') }}`;
    formAction = formAction.replace(':id', exam.id);
    document.getElementById('editExamForm').action = formAction;

    document.getElementById('edit-name').value = exam.name;
    document.getElementById('edit-description').value = exam.description;
    document.getElementById('edit-due_date').value = exam.due_date;

    // Limpiar el campo de archivo
    document.getElementById('edit-file').value = '';

    document.getElementById('editExamModal').style.display = 'block';
    }

    function closeEditExamModal() {
        document.getElementById('editExamModal').style.display = 'none';
    }
</script>
@endsection
