@extends('layouts.app')
@section('title', 'Modifier le chapitre')
@section('content')
<h1 class="mb-4">Modifier le chapitre: {{ $cour->titre }}</h1>
<form method="POST" action="{{ route('chapitres.update', $chapitre) }}">
    @csrf
    @method('PUT')
    @include('chapitres._form', ['chapitre' => $chapitre])
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>
@endsection
