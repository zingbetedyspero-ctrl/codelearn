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

<div class="mt-4 d-flex gap-2 flex-wrap">
    @if ($evaluationChapitre)
        <a href="{{ route('tentatives.create', $evaluationChapitre) }}" class="btn btn-primary">
            Chapitre terminé — Passer le test
        </a>
    @endif

    @if ($estIntroduction)
        @if ($accesComplet && $chapitreSuivant)
            {{-- Le test du chapitre 1 est optionnel : accès déjà payé, on peut continuer directement. --}}
            <a href="{{ route('catalogue.chapitre', [$cour, $chapitreSuivant]) }}" class="btn btn-outline-secondary">
                Continuer sans passer le test &rarr;
            </a>
        @elseif (! $accesComplet)
            <a href="{{ route('paiements.initier', $cour) }}" class="btn btn-outline-secondary">
                Débloquer l'accès complet pour continuer
            </a>
        @endif
    @endif
</div>
@endsection