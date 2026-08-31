@extends('layouts.app')
@section('title', 'Nouveau chapitre')
@section('content')
<h1 class="mb-4">Nouveau chapitre: {{ $cour->titre }}</h1>
<form method="POST" action="{{ route('cours.chapitres.store', $cour) }}">
    @csrf
    @include('chapitres._form')
    <button type="submit" class="btn btn-primary">Créer</button>
</form>
@endsection
