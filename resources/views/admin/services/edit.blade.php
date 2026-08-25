@extends('admin.layouts.app')

@section('title', $service->name)
@section('pageTitle', 'Servicios')

@section('content')
<section class="page-heading">
    <div>
        <a class="back-link" href="{{ route('admin.services.index') }}">
            ← Volver a servicios
        </a>
        <h1>{{ $service->name }}</h1>
        <p>Edita el contenido comercial y la publicación del servicio.</p>
    </div>
</section>

<form method="POST" action="{{ route('admin.services.update', $service) }}">
    @csrf
    @method('PUT')
    @include('admin.services._form')
</form>
@include('admin.services._content')
@include('admin.services._media')
@endsection
