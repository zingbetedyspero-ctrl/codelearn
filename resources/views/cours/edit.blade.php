@extends('layouts.app')
@section('title', 'Modifier le cours')
@section('content')
<h1 class="mb-4">Modifier le cours</h1>
<form method="POST" action="{{ route('cours.update', $cour) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('cours._form', ['categories' => $categories, 'cour' => $cour])
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>
@endsection
