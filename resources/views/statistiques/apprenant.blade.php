@extends('layouts.app')
@section('title', 'Mon espace')

@section('content')
<style>
    .espace-header {
        margin-bottom: 3rem;
    }
    .espace-header h1 {
        font-weight: 700;
        font-size: 1.9rem;
        background: linear-gradient(90deg, var(--cl-bleu), var(--cl-orange));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: inline-block;
    }
    .section-title {
        font-weight: 600;
        font-size: 1.1rem;
        color: #1a1a1a;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .section-title::before {
        content: '';
        width: 6px;
        height: 20px;
        border-radius: 4px;
        background: linear-gradient(180deg, var(--cl-bleu), var(--cl-orange));
        display: inline-block;
    }
 
    /* --- Cartes formations --- */
    .formation-card {
        position: relative;
        border: none;
        border-radius: 18px;
        padding: 1.75rem;
        background: #fff;
        box-shadow: 0 2px 10px rgba(29,78,216,0.08);
        transition: box-shadow 0.25s ease, transform 0.25s ease;
        height: 100%;
        overflow: hidden;
    }
    .formation-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--cl-bleu), var(--cl-orange));
    }
    .formation-card:hover {
        box-shadow: 0 12px 28px rgba(29,78,216,0.18);
        transform: translateY(-3px);
    }
    .formation-card h5 {
        font-weight: 700;
        font-size: 1.05rem;
        margin-bottom: 1.25rem;
        color: var(--cl-bleu-fonce);
    }
    .progress-color {
        height: 8px;
        border-radius: 999px;
        background-color: var(--cl-bleu-clair);
        overflow: hidden;
    }
    .progress-color .progress-bar {
        border-radius: 999px;
        background: linear-gradient(90deg, var(--cl-bleu), var(--cl-orange));
    }
    .progress-label {
        font-size: 0.8rem;
        color: #888;
        margin-top: 0.6rem;
        font-weight: 500;
    }
    .badge-color {
        font-weight: 600;
        font-size: 0.72rem;
        padding: 0.4em 0.9em;
        border-radius: 999px;
        letter-spacing: 0.02em;
        border: none;
    }
    .badge-color.bg-success { background: linear-gradient(90deg, #22c55e, #16a34a) !important; color: #fff !important; }
    .badge-color.bg-info { background: linear-gradient(90deg, var(--cl-bleu), var(--cl-bleu-fonce)) !important; color: #fff !important; }
    .badge-color.bg-secondary { background-color: #fef3c7 !important; color: #b45309 !important; }
 
    /* --- Tableaux --- */
    .table-color {
        border-collapse: separate;
        border-spacing: 0 10px;
    }
    .table-color thead th {
        border: none;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--cl-bleu);
        font-weight: 700;
        padding-bottom: 0.5rem;
    }
    .table-color tbody tr {
        background: #fff;
        box-shadow: 0 1px 4px rgba(29,78,216,0.06);
    }
    .table-color tbody tr td:first-child {
        border-radius: 12px 0 0 12px;
    }
    .table-color tbody tr td:last-child {
        border-radius: 0 12px 12px 0;
    }
    .table-color tbody td {
        border: none;
        padding: 1rem 0.9rem;
        vertical-align: middle;
    }
 
    .empty-state {
        padding: 3rem 1.5rem;
        text-align: center;
        color: var(--cl-bleu);
        background: linear-gradient(135deg, var(--cl-bleu-clair), #FFF3E8);
        border-radius: 18px;
        width: 100%;
        font-weight: 500;
    }
    .empty-state a {
        font-weight: 700;
        color: var(--cl-bleu);
        text-decoration: none;
    }
    .btn-gradient {
        background: linear-gradient(90deg, var(--cl-bleu), var(--cl-orange));
        color: #fff;
        border: none;
        font-weight: 600;
        padding: 0.5rem 1.3rem;
        border-radius: 999px;
        transition: opacity 0.2s ease;
    }
    .btn-gradient:hover {
        opacity: 0.9;
        color: #fff;
    }
</style>
 


<div class="espace-header">
    <h1>Mon espace</h1>
</div>

<div class="mb-5">
    <div class="section-title">Mes formations</div>
    <div class="row g-4">
        @forelse ($inscriptions as $inscription)
            @php
                $cour = $inscription->payement->cour ?? null;
            @endphp
            @continue(!$cour)
            @php
                $total = $cour->chapitres()->count();
                $debloque = min($inscription->chapitreDebloqueJusqua(), $total);
                $pourcentage = $total > 0 ? round($debloque / $total * 100) : 0;
            @endphp
            <div class="col-md-4">
                <div class="formation-card">
                    <h5>{{ $cour->titre }}</h5>
                    <div class="progress progress-color">
                        <div class="progress-bar" style="width: {{ $pourcentage }}%"></div>
                    </div>
                    <div class="progress-label">{{ $debloque }} / {{ $total }} chapitres débloqués</div>
                    <span class="badge badge-color {{ $inscription->statut === 'termine' ? 'bg-success' : 'bg-info' }} mt-3">
                        {{ $inscription->statut === 'termine' ? 'Terminé' : 'En cours' }}
                    </span>
                </div>
            </div>
        @empty
            <div class="empty-state">
                Tu n'es inscrit à aucun cours pour le moment.<br>
                <a href="{{ route('catalogue.index') }}">Découvrir le catalogue →</a>
            </div>
        @endforelse
    </div>
</div>

@if ($paiementsEnAttente->isNotEmpty())
<div class="mb-5">
    <div class="section-title">Souscriptions en attente</div>
    <table class="table table-color">
        <thead>
            <tr><th>Cours</th><th>Montant</th><th>Statut</th></tr>
        </thead>
        <tbody>
            @foreach ($paiementsEnAttente as $p)
                <tr>
                    <td>{{ $p->cour->titre }}</td>
                    <td>{{ number_format($p->montant, 0, ',', ' ') }} FCFA</td>
                    <td><span class="badge badge-color bg-secondary">En attente</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<div>
    <div class="section-title">Résultats des évaluations</div>
    <table class="table table-color">
        <thead>
            <tr><th>Évaluation</th><th>Score</th><th>Statut</th><th>Date</th></tr>
        </thead>
        <tbody>
            @forelse ($tentatives as $t)
                <tr>
                    <td>{{ $t->evaluation->titre }}</td>
                    <td>{{ $t->score }}%</td>
                    <td>
                        <span class="badge badge-color {{ $t->statut === 'reussi' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $t->statut }}
                        </span>
                    </td>
                    <td>{{ $t->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Aucune tentative.</td></tr>
            @endforelse
        </tbody>
    </table>
    <a href="{{ route('tentatives.historique') }}" class="btn btn-gradient btn-sm mt-3">
        Voir tout l'historique
    </a>
</div>
@endsection