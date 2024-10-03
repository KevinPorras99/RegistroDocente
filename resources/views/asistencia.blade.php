@extends('sideynavbar')

@section('title', 'Asistencia')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
@endsection

@section('content')
    <div class="content">
        @if (session('success'))
            <div class="alert alert-success" id="success-message">
                {{ session('success') }}
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0"><i class="fas fa-calendar-check"></i> Asistencia</h1>
            <form id="filterForm" action="{{ route('asistencia.show') }}" method="GET" class="d-inline-block">
                <h2><i class="fas fa-filter"></i> Filtros</h2>
                <select id="courseSelect" name="course" class="form-control d-inline-block" style="width: 300px;"
                    onchange="this.form.submit();">
                    <option value="">Seleccione un curso</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}"
                            {{ request('course') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }} - {{ $course->grade }} - {{ $course->institution }} -
                            {{ $course->classroom }}
                        </option>
                    @endforeach
                </select>
                <input type="date" name="start_date" class="form-control d-inline-block ml-2" style="width: 150px;"
                    value="{{ request('start_date') }}">
                <input type="date" name="end_date" class="form-control d-inline-block ml-2" style="width: 150px;"
                    value="{{ request('end_date') }}">
                <button type="submit" class="btn btn-primary ml-2 mt-md-0">Filtrar</button>
            </form>
        </div>

        <!-- Barra de búsqueda y botón para agregar asistencia -->
        <div class="d-flex flex-wrap mb-3">
            <form action="{{ route('asistencia.show') }}" method="GET" class="mr-2 flex-grow-1 d-flex">
                <input type="hidden" name="course" value="{{ request('course') }}">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <input type="text" name="search" placeholder="Buscar estudiante..." class="form-control"
                    style="width: 60%;" {{ (!request('start_date') || !request('end_date')) ? 'disabled' : '' }}>
                <button type="submit" class="btn btn-primary ml-2 mt-md-0" {{ (!request('start_date') || !request('end_date')) ? 'disabled' : '' }}>Buscar</button>
                <a href="{{ route('asistencia') }}" class="btn btn-secondary ml-2 mt-md-0">Borrar Filtros</a>
            </form>
        </div>


        <!-- Mostrar mensaje si no se han seleccionado todos los filtros -->
        @if (!request('course') || !request('start_date') || !request('end_date'))
            <div class="alert alert-warning">
                Por favor, seleccione un curso y un rango de fechas para ver la lista de estudiantes.
            </div>
        @else
            <!-- Lista de estudiantes en formato de calendario -->
            <form action="{{ route('asistencia.store') }}" method="POST">
                @csrf
                <input type="hidden" name="course" value="{{ request('course') }}">
                <div class="container">
                    <h1>Lista de Estudiantes</h1>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre del Estudiante</th>
                                    @foreach ($dates as $date)
                                        <th>{{ $date->format('d-m-Y') }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <td>{{ $student->name }}</td>
                                    @foreach ($dates as $date)
                                        <td>
                                            @php
                                                $attendance = $student->assistances->firstWhere('date', $date->format('Y-m-d'));
                                            @endphp
                                            <select name="attendance[{{ $student->id }}][{{ $date->format('Y-m-d') }}]" class="form-control">
                                                <option value="">Sin asignar</option>
                                                <option value="present" {{ $attendance && $attendance->status == 'present' ? 'selected' : '' }}>Presente</option>
                                                <option value="late" {{ $attendance && $attendance->status == 'late' ? 'selected' : '' }}>Tardía</option>
                                                <option value="absent" {{ $attendance && $attendance->status == 'absent' ? 'selected' : '' }}>Ausente</option>
                                            </select>
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Botón para guardar asistencia -->
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-success">Guardar Asistencia</button>
                </div>
            </form>

            <!-- Enlaces de paginación -->
            <div class="d-flex justify-content-center">
                {{ $students->links('pagination::bootstrap-4') }}
            </div>
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