@extends('sideynavbar')

@section('title', 'Añadir Calificaciones')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
@endsection

@section('content')
    <div class="container">
        <h2>Añadir Calificaciones para el Curso: {{ $course->name }}</h2>
        <form action="{{ route('grades.store') }}" method="POST">
            @csrf
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <input type="hidden" name="cycle" value="{{ $cycle }}">
            <table class="table">
                <thead>
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
                                    <input type="number" name="grades[{{ $student->id }}][{{ $dailyWork->id }}]" value="{{ $dailyWork->grades[$student->id] ?? '' }}" min="0" max="100">
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Guardar Calificaciones</button>
        </form>
    </div>
@endsection