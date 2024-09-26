@extends('sideynavbar')

@section('title', 'Añadir Calificaciones')



@section('content')
    <div class="container pt-5">
        <!-- Botón de flecha hacia la izquierda solo con el icono en la esquina superior izquierda -->
        <div class="back-button">
            <a href="{{ route('dailyWorks.index') }}" class="btn btn-secondary btn-lg rounded-circle" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>

        <h1 class="mb-4">Añadir Calificaciones para el Curso: {{ $course->name }}</h1>
        <h2 class="mb-4">Ciclo: {{ $course->cycle_number }}</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('grades.store') }}" method="POST">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <input type="hidden" name="cycle" value="{{ $cycle }}">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Estudiante</th>
                            @foreach ($dailyWorks as $dailyWork)
                                <th>{{ $dailyWork->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                                @foreach ($dailyWorks as $dailyWork)
                                    <td>
                                        <input type="number" class="form-control" name="grades[{{ $student->id }}][{{ $dailyWork->id }}]" value="{{ $dailyWork->grades[$student->id] ?? '' }}" min="0" max="100">
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <button type="submit" class="btn btn-primary">Guardar Calificaciones</button>
            </div>
        </form>
    </div>
@endsection
