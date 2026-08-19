@extends('layouts.app')
@section('title', "Modifier l'évaluation")
@section('content')
<h1 class="mb-4">Modifier l'évaluation — {{ $cour->titre }}</h1>
<form method="POST" action="{{ route('evaluations.update', $evaluation) }}">
    @csrf
    @method('PUT')
    @include('evaluations._form', ['chapitres' => $chapitres, 'evaluation' => $evaluation])
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>
@endsection
