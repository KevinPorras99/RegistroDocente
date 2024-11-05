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
            <select id="courseSelect" name="course" class="form-control d-inline-block" style="width: 300px;" onchange="updateCycleOptions();">
                <option value="">Seleccione un curso</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" data-cycles="{{ $course->cycle }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                        {{ $course->name }} - {{ $course->grade }} - {{ $course->institution }} - {{ $course->classroom }}
                    </option>
                @endforeach
            </select>
            <select id="cycleSelect" name="cycle" class="form-control d-inline-block ml-2" style="width: 150px;">
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
            <button type="button" class="btn btn-primary ml-2" onclick="checkFiltersAndSubmit();">Filtrar</button>
        </form>
    </div>

    <!-- Barra de búsqueda y botón para agregar conducta -->
    <div class="d-flex flex-wrap mb-3">
        <form action="{{ route('conducta.show') }}" method="GET" class="mr-2 flex-grow-1 d-flex">
            <input type="hidden" name="course" value="{{ request('course') }}">
            <input type="text" name="search" placeholder="Buscar estudiante..." class="form-control" style="width: 60%;" {{ (!request('course')) ? 'disabled' : '' }}>
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
        <form action="{{ route('conducta.storeGrades') }}" method="POST">
            @csrf
            <input type="hidden" name="course" value="{{ request('course') }}">
            <input type="hidden" name="cycle" value="{{ request('cycle') }}">
            <div class="container">
                <h1>Lista de Estudiantes</h1>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre del Estudiante</th>
                                <th>Ciclo</th>
                                <th>Conducta</th>
                                <th>Calificar</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>{{ request('cycle') }}</td>
                                <td>
                                    <select name="conduct[{{ $student->id }}]" class="form-control">
                                        <option value="">Sin asignar</option>
                                        <option value="good" {{ $student->conducts->where('cycle', request('cycle'))->first()?->conduct == 'good' ? 'selected' : '' }}>Buena</option>
                                        <option value="average" {{ $student->conducts->where('cycle', request('cycle'))->first()?->conduct == 'average' ? 'selected' : '' }}>Regular</option>
                                        <option value="poor" {{ $student->conducts->where('cycle', request('cycle'))->first()?->conduct == 'poor' ? 'selected' : '' }}>Mala</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="grade[{{ $student->id }}]" class="form-control" min="0" max="100" value="{{ $student->conducts->where('cycle', request('cycle'))->first()?->grade ?? '' }}">
                                </td>
                                <td>
                                    <textarea name="observations[{{ $student->id }}]" class="form-control" rows="2">{{ $student->conducts->where('cycle', request('cycle'))->first()?->observations ?? '' }}</textarea>
                                </td>
                            </tr>
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

    function checkFiltersAndSubmit() {
        const courseSelect = document.getElementById('courseSelect').value;
        const cycleSelect = document.getElementById('cycleSelect').value;
        if (courseSelect && cycleSelect) {
            document.getElementById('filterForm').submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('courseSelect').value) {
            updateCycleOptions();
        }
    });

    $(document).ready(function() {
        setTimeout(function() {
            $('#success-message').fadeOut('slow');
        }, 3000); // 3 segundos
    });
</script>
@endsection