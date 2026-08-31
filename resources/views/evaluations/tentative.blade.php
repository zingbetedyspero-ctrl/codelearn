@extends('layouts.app')
@section('title', $evaluation->titre)
@push('styles')
<style>
    .cl-question-manquante { border: 2px solid #DC2626; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>{{ $evaluation->titre }}</h1>
    <div class="badge bg-primary fs-6" id="cl-timer">--:--</div>
</div>
<p class="text-muted">Seuil de réussite : {{ $evaluation->seuil_reussite }}% · Durée max : {{ $evaluation->duree_max }} minutes</p>

<form method="POST" action="{{ route('tentatives.store', $evaluation) }}" id="cl-tentative-form">
    @csrf
    <input type="hidden" name="debut" value="{{ now()->timestamp }}">

    <div id="cl-alerte-qcm" class="alert alert-danger" style="display:none;"></div>

    @foreach ($questions as $i => $question)
        <div class="card mb-3 {{ $question->estQcm() ? 'cl-question-qcm' : '' }}" data-numero="{{ $i + 1 }}">
            <div class="card-body">
                <h5 class="mb-3">{{ $i + 1 }}. {{ $question->enonce }}</h5>

                @if ($question->estQcm())
                    @php $nbCorrectes = $question->optionsReponse->where('is_correct', true)->count(); @endphp
                    <p class="text-muted small mb-2">
                        <i class="bi bi-info-circle"></i>
                        {{ $nbCorrectes > 1 ? 'Plusieurs réponses possibles' : 'Une seule réponse possible' }} — sélection obligatoire.
                    </p>
                    @foreach ($question->optionsReponse as $option)
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" name="reponses[{{ $question->id }}][]" value="{{ $option->id }}" id="opt{{ $option->id }}">
                            <label class="form-check-label" for="opt{{ $option->id }}">{{ $option->option_texte }}</label>
                        </div>
                    @endforeach
                @else
                    <textarea name="reponses[{{ $question->id }}]" rows="4" class="form-control" placeholder="Votre réponse..."></textarea>
                @endif
            </div>
        </div>
    @endforeach

    <button type="submit" class="btn btn-primary btn-lg">Soumettre mes réponses</button>
</form>

@push('scripts')
<script>
    // RG08 - Anti-fraude : détection changement d'onglet / copier-coller, journalisées côté serveur.
    function clJournaliser(type) {
        fetch("{{ route('journal.enregistrer') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ type_action: type }),
        });
    }

    document.addEventListener('visibilitychange', function () {
        if (document.hidden) clJournaliser('changement_onglet');
    });
    document.addEventListener('copy', function () { clJournaliser('copier_coller'); });
    document.addEventListener('paste', function () { clJournaliser('copier_coller'); });

    document.getElementById('cl-tentative-form').addEventListener('submit', function (e) {
        let manquantes = [];

        document.querySelectorAll('.cl-question-qcm').forEach(function (bloc) {
            const cochees = bloc.querySelectorAll('input[type="checkbox"]:checked');
            if (cochees.length === 0) {
                manquantes.push(bloc.dataset.numero);
                bloc.classList.add('cl-question-manquante');
            } else {
                bloc.classList.remove('cl-question-manquante');
            }
        });

        if (manquantes.length > 0) {
            e.preventDefault();
            const alerte = document.getElementById('cl-alerte-qcm');
            alerte.textContent = 'Merci de sélectionner au moins une réponse pour la question ' + manquantes.join(', ') + ' avant de valider.';
            alerte.style.display = 'block';
            alerte.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    // Minuteur global basé sur duree_max, soumission automatique à expiration.
    let clSecondesRestantes = {{ $evaluation->duree_max * 60 }};
    const clTimerEl = document.getElementById('cl-timer');

    function clAfficherTemps() {
        const m = Math.floor(clSecondesRestantes / 60);
        const s = clSecondesRestantes % 60;
        clTimerEl.textContent = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    }
    clAfficherTemps();

    const clInterval = setInterval(function () {
        clSecondesRestantes--;
        clAfficherTemps();
        if (clSecondesRestantes <= 0) {
            clearInterval(clInterval);
            document.getElementById('cl-tentative-form').submit();
        }
    }, 1000);
</script>
@endpush
@endsection
