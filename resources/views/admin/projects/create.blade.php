@extends('admin.layouts.app')

@section('title', 'Nuevo proyecto')
@section('pageTitle', 'Proyectos')

@section('content')
<section class="page-heading">
    <div>
        <a class="back-link" href="{{ route('admin.projects.index') }}">
            ← Volver a proyectos
        </a>

        <h1>Nuevo proyecto</h1>
        <p>Crea un nuevo caso para el portafolio de Somos Constructivos.</p>
    </div>
</section>

<form method="POST" action="{{ route('admin.projects.store') }}">
    @csrf
    @include('admin.projects._form')
</form>
@endsection
