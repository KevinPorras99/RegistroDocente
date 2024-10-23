@extends('sideynavbar')

@section('title', 'Calificaciones')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
<style>
    body {
        font-family: Arial, sans-serif;
    }
    .container {
        width: 90%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    h1 {
        text-align: center;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    table, th, td {
        border: 1px solid black;
    }
    th, td {
        padding: 15px;
        text-align: center;
    }
    .buttons {
        margin-top: 20px;
        text-align: center;
    }
    .btn {
        padding: 10px 15px;
        background-color: #007BFF;
        color: white;
        border: none;
        text-decoration: none;
        border-radius: 5px;
        margin: 5px;
        display: inline-block;
    }
    .btn-primary {
        background-color: #007BFF;
    }
    .btn-secondary {
        background-color: #6c757d;
    }
    .btn-success {
        background-color: #28a745;
    }
    @media (max-width: 768px) {
        .container {
            width: 100%;
            padding: 10px;
        }
        th, td {
            padding: 10px;
        }
        .btn {
            padding: 8px 10px;
            font-size: 14px;
        }
    }
</style>
@endsection

@section('content')
<div class="content">
    <div class="container">
        <h1>Calificaciones</h1>
        <form id="filterForm" action="{{ route('calificaciones.show') }}" method="GET" class="d-inline-block">
            <label for="periodo">Periodo:</label>
            <select id="periodo" name="periodo" class="form-control d-inline-block" style="width: 150px;">
                <option value="1">1° periodo</option>
            </select>
            <label for="institucion">Institución:</label>
            <select id="institucion" name="institucion" class="form-control d-inline-block" style="width: 300px;">
                <option value="liceo">LICEO CARRILLOS DE POÁS</option>
            </select>
            <label for="asignatura">Asignatura:</label>
            <select id="asignatura" name="asignatura" class="form-control d-inline-block" style="width: 150px;">
                <option value="quimica">Química</option>
            </select>
            <label for="grupo">Grupo:</label>
            <select id="grupo" name="grupo" class="form-control d-inline-block" style="width: 150px;">
                <option value="undecimo">UNDÉCIMO AÑO-2</option>
            </select>
        </form>

        <table>
            <tr>
                <th>Trabajo cotidiano</th>
                <th>Tareas</th>
                <th>Pruebas</th>
                <th>Asistencia</th>
            </tr>
            <tr>
                <td>35%</td>
                <td>10%</td>
                <td>45%</td>
                <td>10%</td>
            </tr>
        </table>

        <div class="buttons">
            <a href="#" class="btn btn-primary">DESCARGAR ARCHIVO</a>
            <a href="#" class="btn btn-secondary">SUBIR ARCHIVO</a>
        </div>

        <table>
            <tr>
                <th>Estudiantes</th>
                <th>Trabajo cotidiano</th>
                <th>Tareas</th>
                <th>Pruebas</th>
                <th>Asistencia</th>
                <th>Calificación</th>
            </tr>
            <tr>
                <td>ARAYA MORA JOSHUA STEVEN</td>
                <td>35</td>
                <td>10</td>
                <td>28</td>
                <td>10</td>
                <td>83%</td>
            </tr>
            <tr>
                <td>CARRANZA GONZÁLEZ DORYAN ISAAC</td>
                <td>34</td>
                <td>10</td>
                <td>26</td>
                <td>10</td>
                <td>80%</td>
            </tr>
            <tr>
                <td>CASTRO HERRERA DILAN ANDRÉS</td>
                <td>34</td>
                <td>10</td>
                <td>26</td>
                <td>10</td>
                <td>80%</td>
            </tr>
        </table>
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
