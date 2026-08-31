@extends('layouts.app')

@section('title', 'CodeLearn')
@section('full-width', true)

@push('styles')
<style>
    .cl-hero { background: linear-gradient(180deg, var(--cl-bleu-clair) 0%, var(--cl-blanc) 60%); padding: var(--cl-space-24) 0 var(--cl-space-16); }
    .cl-hero h1 { font-size: var(--cl-text-4xl); line-height: 1.1; }
    @media (max-width: 767px) { .cl-hero h1 { font-size: var(--cl-text-3xl); } }

    .cl-editor {
        background: var(--cl-gris-900);
        border-radius: var(--cl-radius-lg);
        box-shadow: var(--cl-shadow-lg);
        overflow: hidden;
        font-family: var(--cl-font-mono);
        font-size: 0.85rem;
    }
    .cl-editor-bar { background: #1F2937; padding: 10px 14px; display: flex; gap: 6px; }
    .cl-editor-dot { width: 11px; height: 11px; border-radius: 50%; }
    .cl-editor-body { padding: 20px; color: #E5E7EB; line-height: 1.7; }
    .cl-kw { color: #F472B6; } .cl-fn { color: #60A5FA; } .cl-str { color: #34D399; } .cl-com { color: #6B7280; }
    .cl-cursor { display: inline-block; width: 8px; height: 1.1em; background: var(--cl-orange); vertical-align: text-bottom; animation: cl-blink 1s step-end infinite; }
    @keyframes cl-blink { 50% { opacity: 0; } }

    .cl-carousel { border-radius: var(--cl-radius-lg); overflow: hidden; }
    .cl-carousel-slide { height: 340px; background: linear-gradient(135deg, var(--cl-bleu) 0%, var(--cl-bleu-fonce) 100%); display: flex; align-items: center; padding: var(--cl-space-16); }
    .cl-carousel-text { max-width: 520px; }
    @media (max-width: 767px) { .cl-carousel-slide { height: auto; padding: var(--cl-space-8); } }

    .cl-stat { text-align: center; }
    .cl-stat .num { font-family: var(--cl-font-display); font-size: var(--cl-text-3xl); font-weight: 700; color: var(--cl-bleu); }
    .cl-stat .label { color: var(--cl-gris-500); font-size: var(--cl-text-sm); }

    .cl-avantage-icon {
        width: 52px; height: 52px; border-radius: var(--cl-radius-md);
        display: flex; align-items: center; justify-content: center;
        background: var(--cl-bleu-clair); color: var(--cl-bleu); font-size: 1.4rem; margin-bottom: var(--cl-space-4);
    }

    .cl-step-num {
        width: 40px; height: 40px; border-radius: 50%;
        background: var(--cl-bleu); color: white; font-weight: 700;
        display: flex; align-items: center; justify-content: center; font-family: var(--cl-font-display);
        flex-shrink: 0;
    }

    .cl-testimonial-avatar {
        width: 44px; height: 44px; border-radius: 50%;
        background: var(--cl-orange); color: white; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-family: var(--cl-font-display);
    }

    .cl-faq-item { border-bottom: 1px solid var(--cl-gris-200); }
    .cl-faq-question {
        width: 100%; text-align: left; background: none; border: none; padding: var(--cl-space-6) 0;
        font-weight: 600; font-size: var(--cl-text-lg); display: flex; justify-content: space-between; align-items: center;
        color: var(--cl-gris-900);
    }
    .cl-faq-answer { display: none; padding-bottom: var(--cl-space-6); color: var(--cl-gris-700); }
    .cl-faq-item.open .cl-faq-answer { display: block; }
    .cl-faq-item.open .cl-faq-chevron { transform: rotate(180deg); }
    .cl-faq-chevron { transition: transform var(--cl-transition-base); }

    .cl-cta-banner {
        background: linear-gradient(135deg, var(--cl-bleu) 0%, var(--cl-bleu-fonce) 100%);
        border-radius: var(--cl-radius-lg);
        color: white;
        padding: var(--cl-space-16);
    }
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="cl-hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="cl-eyebrow">Plateforme e-learning</span>
                <h1>Apprenez à coder. <br>Validez vos compétences. <br>Décrochez votre <span style="color: var(--cl-bleu);">certificat</span>.</h1>
                <p class="mt-3 mb-4" style="color: var(--cl-gris-700); font-size: var(--cl-text-lg);">
                    CodeLearn vous accompagne du premier chapitre gratuit jusqu'à l'examen final,
                    avec une progression guidée, des tests à chaque étape et une certification
                    numérique vérifiable.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="btn btn-cta btn-lg">Commencer maintenant</a>
                    <a href="{{ route('catalogue.index') }}" class="btn btn-outline-secondary btn-lg">Découvrir les formations</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="cl-editor">
                    <div class="cl-editor-bar">
                        <span class="cl-editor-dot" style="background:#FF5F56;"></span>
                        <span class="cl-editor-dot" style="background:#FFBD2E;"></span>
                        <span class="cl-editor-dot" style="background:#27C93F;"></span>
                    </div>
                    <div class="cl-editor-body">
<pre style="margin:0;"><span class="cl-com">// chapitre_1.php — introduction gratuite</span>
<span class="cl-kw">function</span> <span class="cl-fn">devenirDeveloppeur</span>(<span class="cl-kw">$apprenant</span>) {
    <span class="cl-kw">$apprenant</span>->étudier(<span class="cl-str">'chapitre 1'</span>);
    <span class="cl-kw">$apprenant</span>->passerLeTest();

    <span class="cl-kw">if</span> (<span class="cl-kw">$apprenant</span>->score >= <span class="cl-str">85</span>) {
        <span class="cl-kw">return</span> <span class="cl-str">'Chapitre suivant débloqué '</span>;
    }
}<span class="cl-cursor"></span></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CARROUSEL --}}
@if ($coursVedette->isNotEmpty())
<section class="py-5">
    <div class="container">
        <div id="clCarousel" class="carousel slide cl-carousel" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach ($coursVedette as $i => $c)
                    <button type="button" data-bs-target="#clCarousel" data-bs-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach ($coursVedette as $i => $c)
                    <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                        <div class="cl-carousel-slide">
                            <div class="cl-carousel-text">
                                <span class="cl-eyebrow" style="background: rgba(255,255,255,0.15); color: white;">{{ $c->categorie->nom ?? 'Formation' }}</span>
                                <h3 class="text-white">{{ $c->titre }}</h3>
                                <p style="color: rgba(255,255,255,0.85);">{{ \Illuminate\Support\Str::limit($c->description, 110) }}</p>
                                <a href="{{ route('catalogue.show', $c) }}" class="btn btn-cta">Découvrir ce cours</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#clCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#clCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
</section>
@endif

{{-- COURS EN VEDETTE --}}
@if ($coursVedette->isNotEmpty())
<section class="cl-section-alt">
    <div class="container">
        <div class="text-center mb-5">
            <span class="cl-eyebrow">À la une</span>
            <h2>Formations récentes</h2>
        </div>
        <div class="row g-4">
            @foreach ($coursVedette as $c)
                <div class="col-md-4">
                    <div class="card h-100">
                        @if ($c->image_couverture)
                            <img src="{{ Storage::url($c->image_couverture) }}" class="card-img-top" style="height:160px;object-fit:cover;">
                        @else
                            <div style="height:160px; background: var(--cl-bleu-clair); display:flex; align-items:center; justify-content:center;">
                                <i class="bi bi-code-slash" style="font-size:2.5rem; color: var(--cl-bleu);"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $c->titre }}</h5>
                            <p class="text-muted small">{{ $c->categorie->nom ?? 'Sans catégorie' }} · {{ number_format($c->prix, 0, ',', ' ') }} FCFA</p>
                            <a href="{{ route('catalogue.show', $c) }}" class="btn btn-outline-primary btn-sm">Voir le cours</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- STATS --}}
<section class="cl-section-alt py-5">
    <div class="container">
        <div class="row">
            <div class="col-4 cl-stat">
                <div class="num">{{ $stats['apprenants'] }}+</div>
                <div class="label">Apprenants inscrits</div>
            </div>
            <div class="col-4 cl-stat">
                <div class="num">{{ $stats['formations'] }}</div>
                <div class="label">Formations publiées</div>
            </div>
            <div class="col-4 cl-stat">
                <div class="num">{{ $stats['certificats'] }}</div>
                <div class="label">Certificats délivrés</div>
            </div>
        </div>
    </div>
</section>

{{-- AVANTAGES --}}
<section class="cl-section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="cl-eyebrow">Pourquoi CodeLearn</span>
            <h2>Une formation pensée pour votre réussite</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="cl-avantage-icon"><i class="bi bi-bullseye"></i></div>
                <h5>Progression guidée</h5>
                <p class="text-muted">Chaque chapitre se valide par un test avant de débloquer le suivant.</p>
            </div>
            <div class="col-md-3">
                <div class="cl-avantage-icon"><i class="bi bi-unlock"></i></div>
                <h5>Introduction gratuite</h5>
                <p class="text-muted">Découvrez le premier chapitre de chaque cours sans engagement.</p>
            </div>
            <div class="col-md-3">
                <div class="cl-avantage-icon"><i class="bi bi-patch-check"></i></div>
                <h5>Certification vérifiable</h5>
                <p class="text-muted">Un certificat numérique avec code de vérification unique.</p>
            </div>
            <div class="col-md-3">
                <div class="cl-avantage-icon"><i class="bi bi-credit-card"></i></div>
                <h5>Paiement local</h5>
                <p class="text-muted">Payez facilement via FedaPay, en FCFA.</p>
            </div>
        </div>
    </div>
</section>

{{-- CATEGORIES --}}
@if ($categories->isNotEmpty())
<section class="cl-section-alt">
    <div class="container">
        <div class="text-center mb-5">
            <span class="cl-eyebrow">Catalogue</span>
            <h2>Explorez nos catégories de cours</h2>
        </div>
        <div class="row g-4">
            @foreach ($categories as $categorie)
                <div class="col-md-4">
                    <div class="card h-100 p-4">
                        <h5>{{ $categorie->nom }}</h5>
                        <p class="text-muted small mb-3">{{ \Illuminate\Support\Str::limit($categorie->description, 80) }}</p>
                        <span class="badge bg-info">{{ $categorie->cours_count }} formation(s)</span>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('catalogue.index') }}" class="btn btn-primary">Voir tout le catalogue</a>
        </div>
    </div>
</section>
@endif

{{-- COMMENT CA MARCHE --}}
<section class="cl-section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="cl-eyebrow">Fonctionnement</span>
            <h2>Comment ça marche ?</h2>
        </div>
        <div class="row g-5">
            <div class="col-md-4 d-flex gap-3">
                <div class="cl-step-num">1</div>
                <div>
                    <h5>Inscrivez-vous gratuitement</h5>
                    <p class="text-muted">Créez votre compte et accédez immédiatement à l'introduction de chaque cours.</p>
                </div>
            </div>
            <div class="col-md-4 d-flex gap-3">
                <div class="cl-step-num">2</div>
                <div>
                    <h5>Étudiez et validez chaque chapitre</h5>
                    <p class="text-muted">Débloquez la suite en réussissant les tests à 85% minimum.</p>
                </div>
            </div>
            <div class="col-md-4 d-flex gap-3">
                <div class="cl-step-num">3</div>
                <div>
                    <h5>Obtenez votre certificat</h5>
                    <p class="text-muted">Réussissez l'examen final (80%) et recevez votre certificat vérifiable.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CERTIFICATS --}}
<section class="cl-section-alt">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="cl-eyebrow">Certification</span>
                <h2>Un certificat numérique, vérifiable par tous</h2>
                <p class="text-muted">
                    Chaque certificat CodeLearn dispose d'un code de vérification unique.
                    Employeurs et partenaires peuvent confirmer son authenticité en ligne, à tout moment.
                </p>
                <ul class="list-unstyled">
                    <li class="mb-2"> Numéro et code de vérification uniques</li>
                    <li class="mb-2"> Généré automatiquement après l'examen final</li>
                    <li class="mb-2"> Page de vérification publique</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="card p-4" style="border-top: 4px solid var(--cl-orange);">
                    <div class="d-flex justify-content-between mb-3">
                        <strong class="cl-display">Certificat CodeLearn</strong>
                        <span class="badge bg-success">Vérifié</span>
                    </div>
                    <p class="mb-1 text-muted small">Décerné à</p>
                    <p class="fw-bold mb-3">Nom de l'apprenant</p>
                    <p class="mb-1 text-muted small">Code de vérification</p>
                    <p class="cl-mono">CL-XXXX-XXXX-XXXX</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- {{-- TEMOIGNAGES --}}
<section class="cl-section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="cl-eyebrow">Ils ont réussi</span>
            <h2>Ce que disent nos apprenants</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 p-4">
                    <p class="text-muted">"Le déblocage progressif des chapitres m'a vraiment forcé à bien assimiler chaque notion avant d'avancer."</p>
                    <div class="d-flex align-items-center gap-3 mt-3">
                        <div class="cl-testimonial-avatar">A</div>
                        <div><strong>Aïcha</strong><br><span class="text-muted small">Apprenante certifiée</span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4">
                    <p class="text-muted">"Pouvoir tester gratuitement le premier chapitre avant de payer m'a convaincu de la qualité du contenu."</p>
                    <div class="d-flex align-items-center gap-3 mt-3">
                        <div class="cl-testimonial-avatar">K</div>
                        <div><strong>Kossi</strong><br><span class="text-muted small">Apprenant certifié</span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4">
                    <p class="text-muted">"Le paiement en FCFA via FedaPay est instantané, aucune friction pour débloquer la suite du cours."</p>
                    <div class="d-flex align-items-center gap-3 mt-3">
                        <div class="cl-testimonial-avatar">S</div>
                        <div><strong>Sandrine</strong><br><span class="text-muted small">Apprenante certifiée</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->

{{-- FAQ --}}
<section class="cl-section-alt">
    <div class="container" style="max-width: 760px;">
        <div class="text-center mb-5">
            <span class="cl-eyebrow">Questions fréquentes</span>
            <h2>Tout ce qu'il faut savoir</h2>
        </div>
        <div id="cl-faq">
            <div class="cl-faq-item">
                <button class="cl-faq-question" onclick="this.parentElement.classList.toggle('open')">
                    Le premier chapitre est-il vraiment gratuit ? <span class="cl-faq-chevron">▾</span>
                </button>
                <div class="cl-faq-answer">Oui, l'introduction de chaque cours est accessible gratuitement à tout apprenant inscrit, sans aucun paiement.</div>
            </div>
            <div class="cl-faq-item">
                <button class="cl-faq-question" onclick="this.parentElement.classList.toggle('open')">
                    Que se passe-t-il si j'échoue à un test de chapitre ? <span class="cl-faq-chevron">▾</span>
                </button>
                <div class="cl-faq-answer">Vous gardez l'accès au contenu du chapitre pour réviser et pouvez repasser le test autant de fois que nécessaire jusqu'à obtenir 85%.</div>
            </div>
            <div class="cl-faq-item">
                <button class="cl-faq-question" onclick="this.parentElement.classList.toggle('open')">
                    Quels moyens de paiement sont acceptés ? <span class="cl-faq-chevron">▾</span>
                </button>
                <div class="cl-faq-answer">Les paiements se font en FCFA via FedaPay (mobile money et carte bancaire).</div>
            </div>
            <div class="cl-faq-item">
                <button class="cl-faq-question" onclick="this.parentElement.classList.toggle('open')">
                    Le certificat est-il reconnu et vérifiable ? <span class="cl-faq-chevron">▾</span>
                </button>
                <div class="cl-faq-answer">Chaque certificat dispose d'un code de vérification unique consultable publiquement pour en confirmer l'authenticité.</div>
            </div>
        </div>
    </div>
</section>

{{-- CTA FINAL --}}
<section class="cl-section">
    <div class="container">
        <div class="cl-cta-banner text-center">
            <h2 class="text-white">Prêt à obtenir votre certification ?</h2>
            <p class="mb-4" style="color: rgba(255,255,255,0.85);">Inscrivez-vous gratuitement et commencez dès aujourd'hui.</p>
            <a href="{{ route('register') }}" class="btn btn-cta btn-lg">S'inscrire gratuitement</a>
        </div>
    </div>
</section>
@endsection
