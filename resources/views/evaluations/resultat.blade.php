@extends('layouts.app')
@section('title', 'Résultat')
@section('content')
<div class="text-center py-5">
    @if ($tentative->statut === 'en_attente')
        <i class="bi bi-hourglass-split" style="font-size: 4rem; color: #6c757d;"></i>
        <h1 class="mt-3 text-secondary">Correction en cours</h1>
        <p class="fs-5 mt-2">Les résultats de votre test seront disponibles bientôt, veuillez patienter.</p>
        <p class="text-muted">Tentative n°{{ $tentative->numero_tentative }} · {{ $tentative->evaluation->titre }}</p>

        <div class="mt-4">
            <a href="{{ route('tentatives.resultat', $tentative) }}" class="btn btn-outline-primary">Actualiser</a>
            <a href="{{ route('catalogue.show', $tentative->evaluation->cour) }}" class="btn btn-outline-secondary">Retour au cours</a>
        </div>
    @else
        @if ($tentative->statut === 'reussi')
            <i class="bi bi-patch-check-fill" style="font-size: 4rem; color: var(--cl-vert);"></i>
            <h1 class="mt-3 text-success">Test réussi !</h1>
        @else
            <i class="bi bi-x-circle-fill" style="font-size: 4rem; color: #DC2626;"></i>
            <h1 class="mt-3" style="color:#DC2626;">Test échoué</h1>
        @endif

        <p class="fs-4 mt-2">Score : <strong>{{ $tentative->score }}%</strong> (seuil requis : {{ $tentative->evaluation->seuil_reussite }}%)</p>
        <p class="text-muted">Tentative n°{{ $tentative->numero_tentative }} · {{ $tentative->evaluation->titre }}</p>

        <div class="mt-4">
            @if ($tentative->statut === 'echoue')
                <a href="{{ route('tentatives.create', $tentative->evaluation) }}" class="btn btn-primary">Repasser le test</a>
            @endif
            <a href="{{ route('catalogue.show', $tentative->evaluation->cour) }}" class="btn btn-outline-secondary">Retour au cours</a>
        </div>
    @endif
</div>
@endsection
