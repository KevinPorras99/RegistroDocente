@extends('sideynavbar')

@section('title', 'Código QR del Estudiante')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
@endsection

@section('content')
    <div class="content">
        <h1>Código QR del Estudiante</h1>
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">{{ $student->name }}</h5>
                    <p class="card-text">Grado: {{ $student->grade }}</p>
                    <p class="card-text">Institución: {{ $student->institution }}</p>
                    <p class="card-text">Sección: {{ $student->section }}</p>
                </div>
                <div class="qr-code-container">
                    {!! file_get_contents(public_path($student->qr_code_url)) !!}
                </div>
            </div>
        </div>
        <a href="{{ route('students.index') }}" class="btn btn-primary mt-3">Volver a la lista de estudiantes</a>
    </div>
@endsection