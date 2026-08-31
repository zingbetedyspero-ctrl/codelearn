@extends('layouts.app')
@section('title', 'Modifier la catégorie')
@section('content')
<h1 class="mb-4">Modifier la catégorie</h1>
<form method="POST" action="{{ route('categories.update', $categorie) }}">
    @csrf
    @method('PUT')
    @include('categories._form', ['categorie' => $categorie])
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>
@endsection
