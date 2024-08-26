@extends('sideynavbar')

@section('title', 'Cursos')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
@endsection    

@section('content')
    <div class="content">
        <h1>Cursos</h1>
        
        <!-- Aquí puedes añadir el contenido correspondiente a cada sección -->
        <!-- Barra de búsqueda y botón de agregar curso -->
            <div class="container mb-3">
                <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('cursos') }}" method="GET">
                    <input type="text" name="search" placeholder="Buscar cursos..." class="form-control" style="width: 300px;">
                </form>
                <a href="{{ route('cursos.create') }}" class="btn btn-primary">Agregar Curso</a>

                </div>

                <!-- Lista de cursos -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cursos as $curso)
                            <tr>
                                <td>{{ $curso->id }}</td>
                                <td>{{ $curso->nombre }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">No hay cursos disponibles</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
@endsection