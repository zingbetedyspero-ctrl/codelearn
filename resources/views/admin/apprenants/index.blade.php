@extends('layouts.app')
@section('title', 'Gestion des apprenants')

@section('content')
<style>
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: var(--cl-space-4);
        margin-bottom: var(--cl-space-8);
    }
    .admin-header h1 {
        font-family: var(--cl-font-display);
        font-size: var(--cl-text-3xl);
        color: var(--cl-gris-900);
        margin: 0;
    }

    .admin-search {
        max-width: 380px;
    }
    .admin-search .form-control {
        border-radius: var(--cl-radius-md);
        border: 1px solid var(--cl-gris-200);
        padding: 0.55rem 0.9rem 0.55rem 2.2rem;
        background: var(--cl-blanc) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0'/%3E%3C/svg%3E") no-repeat 0.75rem center;
        background-size: 15px;
    }
    .admin-search .form-control:focus {
        border-color: var(--cl-bleu);
        box-shadow: 0 0 0 3px var(--cl-bleu-clair);
    }

    .admin-card {
        background: var(--cl-blanc);
        border-radius: var(--cl-radius-lg);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }
    .admin-table {
        margin-bottom: 0;
    }
    .admin-table thead th {
        font-family: var(--cl-font-display);
        font-size: var(--cl-text-sm);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: var(--cl-blanc);
        background: var(--cl-bleu);
        border-bottom: none;
        padding: 0.9rem 1rem;
        white-space: nowrap;
    }
    .admin-table tbody tr:nth-child(even) {
        background: var(--cl-bleu-clair);
    }
    .admin-table tbody td {
        padding: 0.9rem 1rem;
        vertical-align: middle;
        color: var(--cl-gris-700);
        border-color: var(--cl-gris-200);
    }
    .admin-table tbody tr:hover {
        background: color-mix(in srgb, var(--cl-bleu) 12%, white);
    }
    .admin-table .apprenant-nom {
        font-weight: 600;
        color: var(--cl-gris-900);
    }

    .badge-statut {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.4em 0.9em;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 0.4em;
    }
    .badge-statut::before {
        content: '';
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
    }
    .badge-statut.actif {
        background: #dcfce7;
        color: #15803d;
    }
    .badge-statut.actif::before {
        background: #22c55e;
    }
    .badge-statut.inactif {
        background: #fee2e2;
        color: #b91c1c;
    }
    .badge-statut.inactif::before {
        background: #ef4444;
    }

    .btn-toggle-statut {
        border-radius: var(--cl-radius-md);
    }

    .admin-empty {
        text-align: center;
        color: var(--cl-gris-500);
        padding: 2rem 1rem;
    }
</style>

<div class="admin-header">
    <div>
        <span class="cl-eyebrow">Administration</span>
        <h1>Gestion des apprenants</h1>
    </div>
    <form method="GET" class="admin-search">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, prénom ou email" class="form-control">
    </form>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Statut</th>
                    <th>Inscrit le</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($apprenants as $apprenant)
                    <tr>
                        <td class="apprenant-nom">{{ $apprenant->nomComplet() }}</td>
                        <td>{{ $apprenant->email }}</td>
                        <td>{{ $apprenant->telephone }}</td>
                        <td>
                            <span class="badge-statut {{ $apprenant->statut_compte === 'actif' ? 'actif' : 'inactif' }}">
                                {{ $apprenant->statut_compte }}
                            </span>
                        </td>
                        <td>{{ $apprenant->created_at->format('d/m/Y') }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.apprenants.toggle-statut', $apprenant) }}" class="d-inline">
                                @csrf @method('PUT')
                                <button class="btn btn-sm btn-toggle-statut {{ $apprenant->statut_compte === 'actif' ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                        data-bs-toggle="tooltip" title="{{ $apprenant->statut_compte === 'actif' ? 'Désactiver ce compte' : 'Activer ce compte' }}">
                                    <i class="bi {{ $apprenant->statut_compte === 'actif' ? 'bi-slash-circle' : 'bi-check-circle' }}"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="admin-empty">Aucun apprenant trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $apprenants->links() }}
</div>
@endsection