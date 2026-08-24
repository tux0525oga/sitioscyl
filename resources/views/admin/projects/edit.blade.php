@extends('admin.layouts.app')

@section('title', $project->name)
@section('pageTitle', 'Proyectos')

@section('content')
<section class="page-heading">
    <div>
        <a class="back-link" href="{{ route('admin.projects.index') }}">
            ← Volver a proyectos
        </a>

        <h1>{{ $project->name }}</h1>
        <p>Edita la información del proyecto y su clasificación.</p>
    </div>
</section>

<form method="POST" action="{{ route('admin.projects.update', $project) }}">
    @csrf
    @method('PUT')
    @include('admin.projects._form')
</form>
@endsection
