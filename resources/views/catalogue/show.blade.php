@extends('layouts.app')
@section('title', $cour->titre)
@section('full-width', true)

@push('styles')
<style>
    .cl-course-hero {
        background: linear-gradient(135deg, var(--cl-bleu) 0%, var(--cl-bleu-fonce) 100%);
        color: white; padding: var(--cl-space-16) 0;
    }
    .cl-course-hero .badge-niveau { background: rgba(255,255,255,0.15); }
    .cl-stat-pill { background: var(--cl-gris-50); border-radius: var(--cl-radius-md); padding: var(--cl-space-4); text-align: center; }
    .cl-stat-pill .num { font-family: var(--cl-font-display); font-weight: 700; font-size: 1.4rem; color: var(--cl-bleu); }
    .cl-purchase-card { position: sticky; top: 20px; }
</style>
@endpush

@section('content')
<section class="cl-course-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge badge-niveau mb-2">{{ $cour->categorie->nom ?? 'Formation' }} · {{ ucfirst($cour->niveau) }}</span>
                <h1 class="text-white">{{ $cour->titre }}</h1>
                <p style="color: rgba(255,255,255,0.85); font-size: 1.1rem;">{{ \Illuminate\Support\Str::limit($cour->description, 160) }}</p>
                <div class="d-flex gap-4 mt-3">
                    <div><i class="bi bi-list-ol"></i> {{ $chapitres->count() }} chapitres</div>
                    <div><i class="bi bi-people"></i> {{ $nbEtudiants }} étudiant(s) inscrit(s)</div>
                    <div><i class="bi bi-patch-check"></i> Certificat inclus</div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container mt-5">
    <div class="row g-5">
        <div class="col-lg-8">
            <h2 class="mb-3">À propos de cette formation</h2>
            <p>{{ $cour->description }}</p>

            <div class="row g-3 my-4">
                <div class="col-4"><div class="cl-stat-pill"><div class="num">{{ $chapitres->count() }}</div><div class="text-muted small">Chapitres</div></div></div>
                @if (auth()->user()->estAdministrateur())
                   <div class="col-4"><div class="cl-stat-pill"><div class="num">{{ $nbEtudiants }}</div><div class="text-muted small">Étudiants</div></div></div>
                @endif
                <div class="col-4"><div class="cl-stat-pill"><div class="num">{{ ucfirst($cour->niveau) }}</div><div class="text-muted small">Niveau</div></div></div>
            </div>

            <h3 class="mt-4 mb-3">Programme du cours</h3>
            <div class="list-group mb-4">
                @forelse ($chapitres as $chapitre)
                    @php
                        $estIntro = $chapitre->ordre_affichage === 1;
                        $debloque = $estIntro || $chapitre->ordre_affichage <= $chapitreDebloqueJusqua;
                        $evaluationChapitre = $evaluations->first(fn ($e) => $e->chapitre_id === $chapitre->id);
                    @endphp
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            {{ $chapitre->ordre_affichage }}. {{ $chapitre->titre }}
                            @if ($estIntro)<span class="badge bg-success ms-2">Gratuit</span>@endif
                        </span>
                        <span>
                            @if ($debloque)
                                <a href="{{ route('catalogue.chapitre', [$cour, $chapitre]) }}" class="btn btn-sm btn-outline-primary">Lire</a>
                                @auth
                                    @if ($evaluationChapitre)
                                        <a href="{{ route('tentatives.create', $evaluationChapitre) }}" class="btn btn-sm btn-outline-info">Passer le test</a>
                                    @endif
                                @endauth
                            @else
                                <span class="text-muted">🔒 Paiement requis</span>
                            @endif
                        </span>
                    </div>
                @empty
                    <p>Ce cours n'a pas encore de chapitre.</p>
                @endforelse
            </div>

            @if ($examenFinal)
                <div class="card p-3 {{ $examenAccessible ? '' : 'bg-light' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1"><i class="bi bi-award"></i> Examen final</h5>
                            <p class="text-muted small mb-0">Accessible après validation de tous les chapitres (seuil : {{ $examenFinal->seuil_reussite }}%).</p>
                        </div>
                        @auth
                            @if ($examenAccessible)
                                <a href="{{ route('tentatives.create', $examenFinal) }}" class="btn btn-primary">Passer l'examen final</a>
                            @else
                                <span class="text-muted">🔒 Verrouillé</span>
                            @endif
                        @endauth
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card p-4 cl-purchase-card">
                @if ($cour->image_couverture)
                    <img src="{{ Storage::url($cour->image_couverture) }}" class="rounded mb-3" style="width:100%;height:160px;object-fit:cover;">
                @else
                    <div style="height:160px; background: var(--cl-bleu-clair); display:flex; align-items:center; justify-content:center; border-radius: var(--cl-radius-md);" class="mb-3">
                        <i class="bi bi-code-slash" style="font-size:2.5rem; color: var(--cl-bleu);"></i>
                    </div>
                @endif

                <div class="fs-3 fw-bold mb-3">{{ number_format($cour->prix, 0, ',', ' ') }} FCFA</div>

                @auth
                    @if (auth()->user()->estAdministrateur())
                        <a href="{{ route('cours.edit', $cour) }}" class="btn btn-outline-primary w-100"><i class="bi bi-eye"></i> Voir les détails (inventaire admin)</a>
                    @elseif ($dejaAchete && $chapitres->isNotEmpty())
                        <a href="{{ route('catalogue.chapitre', [$cour, $chapitres->first()]) }}" class="btn btn-outline-success w-100 btn-lg">
                            <i class="bi bi-check-circle-fill"></i> Disponible
                        </a>
                    @elseif ($dejaAchete)
                        <span class="btn btn-outline-success w-100 btn-lg disabled">
                            <i class="bi bi-check-circle-fill"></i> Disponible (aucun chapitre publié)
                        </span>
                    @else
                        <a href="{{ route('paiements.initier', $cour) }}" class="btn btn-success w-100 btn-lg">Acheter ce cours</a>
                    @endif
                @else
                    <a href="{{ route('login', ['redirect' => route('catalogue.show', $cour)]) }}" class="btn btn-success w-100 btn-lg">Suivre ce cours</a>
                @endauth

                <ul class="list-unstyled small text-muted mt-3 mb-0">
                    <li class="mb-1"><i class="bi bi-check-circle text-success"></i> Accès à vie</li>
                    <li class="mb-1"><i class="bi bi-check-circle text-success"></i> Certificat vérifiable</li>
                    <li class="mb-1"><i class="bi bi-check-circle text-success"></i> Paiement sécurisé (FedaPay)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection