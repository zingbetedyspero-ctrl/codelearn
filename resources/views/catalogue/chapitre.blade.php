@extends('layouts.app')
@section('title', $chapitre->titre)
@section('content')
<p><a href="{{ route('catalogue.show', $cour) }}">&larr; Retour au cours {{ $cour->titre }}</a></p>
<h1 class="mb-3">{{ $chapitre->titre }}</h1>
@if ($estIntroduction)
    <span class="badge bg-success mb-3">Introduction gratuite</span>
@endif
<div class="border rounded p-3">
    {!! $chapitre->contenu !!}
</div>
@endsection