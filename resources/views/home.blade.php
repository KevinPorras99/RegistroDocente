@extends('sideynavbar')

@section('title', 'Inicio')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/home.styles.css') }}">
@endsection

@section('content')
    <div class="content">
        <h1><i class="fas fa-home"></i> Inicio</h1>
        <p>Bienvenido al sistema de registro docente.</p>
    </div>
@endsection

