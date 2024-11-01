@extends('sideynavbar')

@section('title', 'Calificaciones')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
@endsection

@section('content')
<div class="content">
    <div class="container">
        <h1>Calificaciones</h1>
        <form method="GET" action="{{ route('calificaciones.showGrades') }}">
            <div class="form-group">
                <label for="course">Curso</label>
                <select name="course" id="course" class="form-control">
                    <option value="">Seleccione un curso</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="cycle">Ciclo</label>
                <input type="text" name="cycle" id="cycle" class="form-control" placeholder="Ingrese el ciclo">
            </div>
            <div class="form-group">
                <label for="search">Buscar estudiante</label>
                <input type="text" name="search" id="search" class="form-control" placeholder="Ingrese el nombre del estudiante">
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

        @if(isset($students) && $students->count() > 0)
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Curso</th>
                        <th>Ciclo</th>
                        <th>Calificación</th>
                        <th>Porcentaje Total de Trabajos Cotidianos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        @foreach($student->calificaciones as $calificacion)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>{{ $calificacion->course->name }}</td>
                                <td>{{ $calificacion->cycle }}</td>
                                <td>{{ $calificacion->grade }}</td>
                                <td>
                                    @php
                                        $totalPercentage = $student->dailyWorkGrades->where('dailyWork.course_id', $calificacion->course_id)->sum('percentage_obtained');
                                    @endphp
                                    {{ $totalPercentage }}%
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No se encontraron calificaciones.</p>
        @endif
    </div>
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