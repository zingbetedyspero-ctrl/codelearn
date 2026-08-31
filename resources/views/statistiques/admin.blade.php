@extends('layouts.app')

@section('title', 'Inventaire')

@push('styles')
<style>
    .cl-admin-header {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        margin-bottom: var(--cl-space-16);
        flex-wrap: wrap;
        gap: var(--cl-space-4);
    }
    .cl-admin-header h1 { margin: 0; }

    .cl-group { margin-bottom: var(--cl-space-24); }
    .cl-group-title {
        display: flex;
        align-items: center;
        gap: var(--cl-space-4);
        margin-bottom: var(--cl-space-6);
    }
    .cl-group-title h2 {
        font-size: var(--cl-text-lg);
        font-family: var(--cl-font-display);
        color: var(--cl-gris-900);
        margin: 0;
    }
    .cl-group-title .cl-eyebrow { margin: 0; }

    .cl-stat-card {
        background: var(--cl-blanc);
        border-radius: var(--cl-radius-lg);
        padding: var(--cl-space-8) var(--cl-space-6);
        text-align: center;
        border: 1px solid var(--cl-gris-200);
        transition: box-shadow var(--cl-transition-base), transform var(--cl-transition-base);
    }
    .cl-stat-card:hover { box-shadow: var(--cl-shadow-lg); transform: translateY(-2px); }
    .cl-stat-card .num {
        font-family: var(--cl-font-display);
        font-size: var(--cl-text-3xl);
        font-weight: 700;
        color: var(--cl-bleu);
        line-height: 1.1;
    }
    .cl-stat-card .num.orange { color: var(--cl-orange); }
    .cl-stat-card .label { color: var(--cl-gris-500); font-size: var(--cl-text-sm); margin-top: 4px; }

    .cl-revenue-card {
        background: linear-gradient(135deg, var(--cl-bleu) 0%, var(--cl-bleu-fonce) 100%);
        border-radius: var(--cl-radius-lg);
        padding: var(--cl-space-16);
        color: white;
        text-align: center;
    }
    .cl-revenue-card .num { font-family: var(--cl-font-display); font-size: var(--cl-text-4xl); font-weight: 700; }
    .cl-revenue-card .label { color: rgba(255,255,255,0.8); font-size: var(--cl-text-sm); margin-top: 4px; }

    .cl-chart-card {
        background: var(--cl-blanc);
        border: 1px solid var(--cl-gris-200);
        border-radius: var(--cl-radius-lg);
        padding: var(--cl-space-8);
        height: 100%;
    }
    .cl-chart-card h6 {
        font-family: var(--cl-font-display);
        color: var(--cl-gris-900);
        margin-bottom: var(--cl-space-6);
    }

    .cl-table-card {
        background: var(--cl-blanc);
        border: 1px solid var(--cl-gris-200);
        border-radius: var(--cl-radius-lg);
        overflow: hidden;
        box-shadow: var(--cl-shadow-sm, 0 1px 2px rgba(16,24,40,0.05));
    }
    .cl-table { margin: 0; border-collapse: separate; border-spacing: 0; width: 100%; }

    .cl-table thead th {
        background: var(--cl-bleu-clair);
        color: var(--cl-bleu-fonce);
        font-family: var(--cl-font-display);
        font-weight: 600;
        font-size: var(--cl-text-sm);
        text-transform: uppercase;
        letter-spacing: 0.03em;
        border: none;
        padding: var(--cl-space-4) var(--cl-space-6);
        white-space: nowrap;
    }
    .cl-table thead th:first-child { padding-left: var(--cl-space-8); }
    .cl-table thead th:last-child { padding-right: var(--cl-space-8); }

    .cl-table tbody td {
        vertical-align: middle;
        color: var(--cl-gris-700);
        padding: var(--cl-space-4) var(--cl-space-6);
        border-top: 1px solid var(--cl-gris-200);
        font-size: var(--cl-text-sm);
    }
    .cl-table tbody td:first-child { padding-left: var(--cl-space-8); color: var(--cl-gris-900); font-weight: 500; }
    .cl-table tbody td:last-child { padding-right: var(--cl-space-8); }

    .cl-table tbody tr:nth-child(even) td { background: var(--cl-section-alt, #F9FAFB); }
    .cl-table tbody tr { transition: background var(--cl-transition-base); }
    .cl-table tbody tr:hover td { background: var(--cl-bleu-clair); }

    .cl-table tbody tr:last-child td { border-bottom: none; }

    .cl-table .cl-mono {
        font-family: var(--cl-font-mono);
        font-size: 0.85rem;
        color: var(--cl-gris-700);
    }

    .cl-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .cl-badge::before {
        content: '';
        width: 6px; height: 6px;
        border-radius: 50%;
        background: currentColor;
    }
    .cl-badge-success { background: #DCFCE7; color: #15803D; }
    .cl-badge-muted { background: var(--cl-gris-200); color: var(--cl-gris-500); }

    .cl-table-empty {
        text-align: center;
        color: var(--cl-gris-500);
        padding: var(--cl-space-16);
        font-size: var(--cl-text-sm);
    }

    @media (max-width: 767px) {
        .cl-table-card { overflow-x: auto; }
        .cl-table { min-width: 560px; }
    }
</style>
@endpush

@section('content')

<div class="cl-admin-header">
    <div>
        <span class="cl-eyebrow">Vue d'ensemble</span>
        <h1>Inventaire de la plateforme</h1>
    </div>
</div>

{{-- FORMATIONS --}}
<div class="cl-group">
    <div class="cl-group-title">
        <span class="cl-eyebrow">Contenu</span>
        <h2>Formations</h2>
    </div>
    <div class="row g-3">
        <div class="col-md-3">
            <div class="cl-stat-card">
                <div class="num">{{ $nbCours }}</div>
                <div class="label">Cours ({{ $nbCoursPublies }} publiés)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="cl-stat-card">
                <div class="num">{{ $nbChapitres }}</div>
                <div class="label">Chapitres</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="cl-stat-card">
                <div class="num">{{ $nbCategories }}</div>
                <div class="label">Catégories</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="cl-stat-card">
                <div class="num orange">{{ $tauxReussite }}%</div>
                <div class="label">Taux de réussite ({{ $nbReussies }}/{{ $nbTentatives }})</div>
            </div>
        </div>
    </div>
</div>

{{-- UTILISATEURS --}}
<div class="cl-group">
    <div class="cl-group-title">
        <span class="cl-eyebrow">Communauté</span>
        <h2>Utilisateurs</h2>
    </div>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="cl-stat-card">
                <div class="num">{{ $nbApprenants }}</div>
                <div class="label">Apprenants</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="cl-stat-card">
                <div class="num">{{ $nbInscriptions }}</div>
                <div class="label">Inscriptions</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="cl-stat-card">
                <div class="num">{{ $nbCoursSuivis }}</div>
                <div class="label">Cours suivis</div>
            </div>
        </div>
    </div>
</div>

{{-- REVENUS --}}
<div class="cl-group">
    <div class="cl-group-title">
        <span class="cl-eyebrow">Finances</span>
        <h2>Revenus</h2>
    </div>
    <div class="cl-revenue-card">
        <div class="num">{{ number_format($revenuTotal, 0, ',', ' ') }} FCFA</div>
        <div class="label">Chiffre d'affaires total</div>
    </div>
</div>

{{-- GRAPHIQUES --}}
<div class="row g-4 cl-group">
    <div class="col-md-6">
        <div class="cl-chart-card">
            <h6>Top 5 cours par revenu</h6>
            <canvas id="clRevenueChart" height="180"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="cl-chart-card">
            <h6>Top 5 cours les plus suivis</h6>
            <canvas id="clPopulariteChart" height="180"></canvas>
        </div>
    </div>
</div>

{{-- REVENUS PAR CATEGORIE --}}
<div class="cl-group">
    <div class="cl-group-title">
        <span class="cl-eyebrow">Détail</span>
        <h2>Revenus par catégorie</h2>
    </div>
    <div class="cl-table-card">
        <table class="cl-table mb-0">
            <thead><tr><th>Catégorie</th><th>Nb. cours</th><th>Revenu</th></tr></thead>
            <tbody>
                @forelse ($revenuParCategorie as $c)
                    <tr>
                        <td>{{ $c->nom }}</td>
                        <td>{{ $c->cours->count() }}</td>
                        <td class="cl-mono">{{ number_format($c->revenu, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="cl-table-empty">Aucune donnée disponible</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- INVENTAIRE COMPLET --}}
<div class="cl-group">
    <div class="cl-group-title">
        <span class="cl-eyebrow">Détail</span>
        <h2>Inventaire complet des cours</h2>
    </div>
    <div class="cl-table-card">
        <table class="cl-table mb-0">
            <thead><tr><th>Titre</th><th>Catégorie</th><th>Chapitres</th><th>Statut</th><th>Prix</th></tr></thead>
            <tbody>
                @forelse ($listeCours as $c)
                    <tr>
                        <td>{{ $c->titre }}</td>
                        <td>{{ $c->categorie->nom ?? '—' }}</td>
                        <td>{{ $c->chapitres_count }}</td>
                        <td>
                            <span class="cl-badge {{ $c->estPublie() ? 'cl-badge-success' : 'cl-badge-muted' }}">
                                {{ $c->estPublie() ? 'Publié' : 'Non publié' }}
                            </span>
                        </td>
                        <td class="cl-mono">{{ number_format($c->prix, 0, ',', ' ') }} FCFA</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="cl-table-empty">Aucun cours enregistré</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
const clBleu = getComputedStyle(document.documentElement).getPropertyValue('--cl-bleu').trim() || '#1D4ED8';
const clOrange = getComputedStyle(document.documentElement).getPropertyValue('--cl-orange').trim() || '#F97316';

new Chart(document.getElementById('clRevenueChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($coursParRevenu->pluck('titre')) !!},
        datasets: [{
            label: 'Revenu (FCFA)',
            data: {!! json_encode($coursParRevenu->pluck('payements_sum_montant')->map(fn($v) => $v ?? 0)) !!},
            backgroundColor: clBleu,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

new Chart(document.getElementById('clPopulariteChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($coursPlusSuivis->pluck('titre')) !!},
        datasets: [{
            label: 'Inscriptions',
            data: {!! json_encode($coursPlusSuivis->pluck('payements_count')) !!},
            backgroundColor: clOrange,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endpush