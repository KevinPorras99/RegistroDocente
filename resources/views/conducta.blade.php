@extends('sideynavbar')

@section('title', 'Conducta')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0"><i class="fas fa-calendar-check"></i> Conducta</h1>
        <form id="filterForm" action="{{ route('conducta.show') }}" method="GET" class="d-inline-block">
            <h2><i class="fas fa-filter"></i> Filtros</h2>
            <select id="courseSelect" name="course" class="form-control d-inline-block" style="width: 300px;" onchange="this.form.submit();">
                <option value="">Seleccione un curso</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                        {{ $course->name }} - {{ $course->grade }} - {{ $course->institution }} - {{ $course->classroom }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Barra de búsqueda y botón para agregar conducta -->
    <div class="d-flex flex-wrap mb-3">
        <form action="{{ route('conducta.show') }}" method="GET" class="mr-2 flex-grow-1 d-flex">
            <input type="hidden" name="course" value="{{ request('course') }}">
            <input type="text" name="search" placeholder="Buscar estudiante..." class="form-control"
                style="width: 60%;" {{ (!request('course')) ? 'disabled' : '' }}>
            <button type="submit" class="btn btn-primary ml-2 mt-md-0" {{ (!request('course')) ? 'disabled' : '' }}>Buscar</button>
            <a href="{{ route('conducta') }}" class="btn btn-secondary ml-2 mt-md-0">Borrar Filtros</a>
        </form>
    </div>

    <!-- Mostrar mensaje si no se ha seleccionado un curso -->
    @if (!request('course'))
        <div class="alert alert-warning">
            Por favor, seleccione un curso para ver la lista de estudiantes.
        </div>
    @else
    @if (session('success'))
        <div class="alert alert-success" id="success-message">
            {{ session('success') }}
        </div>
    @endif
        <!-- Lista de estudiantes -->
        <form action="{{ route('conducta.store') }}" method="POST">
            @csrf
            <input type="hidden" name="course" value="{{ request('course') }}">
            <div class="container">
                <h1>Lista de Estudiantes</h1>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre del Estudiante</th>
                                <th>Conducta</th>
                                <th>Calificar</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>
                                    <select name="conduct[{{ $student->id }}]" class="form-control">
                                        <option value="">Sin asignar</option>
                                        <option value="good" {{ $student->conduct && $student->conduct->status == 'good' ? 'selected' : '' }}>Buena</option>
                                        <option value="average" {{ $student->conduct && $student->conduct->status == 'average' ? 'selected' : '' }}>Regular</option>
                                        <option value="poor" {{ $student->conduct && $student->conduct->status == 'poor' ? 'selected' : '' }}>Mala</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="grade[{{ $student->id }}]" class="form-control" min="0" max="100">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addObservationModal-{{ $student->id }}">Agregar Observaciones</button>
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#viewObservationModal-{{ $student->id }}">Ver Observaciones</button>
                                </td>
                            </tr>

                            <!-- Modal para agregar observaciones -->
                            <div class="modal fade" id="addObservationModal-{{ $student->id }}" tabindex="-1" role="dialog" aria-labelledby="addObservationModalLabel-{{ $student->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addObservationModalLabel-{{ $student->id }}">Agregar Observaciones para {{ $student->name }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <textarea name="observations[{{ $student->id }}]" class="form-control" rows="4"></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary">Guardar Observaciones</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal para ver observaciones -->
                            <div class="modal fade" id="viewObservationModal-{{ $student->id }}" tabindex="-1" role="dialog" aria-labelledby="viewObservationModalLabel-{{ $student->id }}" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewObservationModalLabel-{{ $student->id }}">Observaciones para {{ $student->name }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>{{ $student->observations }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Botón para guardar conducta -->
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-success">Guardar Conducta</button>
            </div>
        </form>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('#success-message').fadeOut('slow');
        }, 3000); // 3 segundos
    });
</script>
@endsection
