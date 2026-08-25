@extends('admin.layouts.app')

@section('title', 'Nuevo servicio')
@section('pageTitle', 'Servicios')

@section('content')
<section class="page-heading">
    <div>
        <a class="back-link" href="{{ route('admin.services.index') }}">
            ← Volver a servicios
        </a>
        <h1>Nuevo servicio</h1>
        <p>Crea una nueva línea de servicio para Somos Constructivos.</p>
    </div>
</section>

<form method="POST" action="{{ route('admin.services.store') }}">
    @csrf
    @include('admin.services._form')
</form>
@endsection
