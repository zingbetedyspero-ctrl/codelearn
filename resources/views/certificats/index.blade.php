@extends('layouts.app')
@section('title', 'Mes certificats')
@section('content')
<h1 class="mb-4">Mes certificats</h1>
<div class="row g-4">
    @forelse ($certificats as $certificat)
        <div class="col-md-4">
            <div class="card p-4" style="border-top: 4px solid var(--cl-orange);">
                <h5>{{ $certificat->inscription->payement->cour->titre ?? 'Cours' }}</h5>
                <p class="text-muted small">Score : {{ $certificat->score_final }}%</p>
                <p class="cl-mono small">{{ $certificat->code_verification }}</p>
                <a href="{{ route('certificats.telecharger', $certificat) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-download"></i> Télécharger le PDF
                </a>
            </div>
        </div>
    @empty
        <p>Tu n'as pas encore obtenu de certificat.</p>
    @endforelse
</div>
@endsection
