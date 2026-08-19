@extends('layouts.app')
@section('title', 'Nouvelle évaluation')
@section('content')
<h1 class="mb-4">Nouvelle évaluation: {{ $cour->titre }}</h1>
<form method="POST" action="{{ route('cours.evaluations.store', $cour) }}">
    @csrf
    @include('evaluations._form', ['chapitres' => $chapitres])
    <button type="submit" class="btn btn-primary">Créer</button>
</form>
@endsection
