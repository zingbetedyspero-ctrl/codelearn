@extends('layouts.app')
@section('title', 'Nouvelles questions')
@section('content')
<h1 class="mb-4">Ajouter des questions: {{ $evaluation->titre }}</h1>

<form method="POST" action="{{ route('evaluations.questions.store-multiple', $evaluation) }}">
    @csrf
    <div id="cl-questions-container"></div>

    <button type="button" class="btn btn-outline-primary mb-4" onclick="clAjouterQuestion()">
        <i class="bi bi-plus-lg"></i> Ajouter une question
    </button>
    <div>
        <button type="submit" class="btn btn-primary">Enregistrer toutes les questions</button>
    </div>
</form>

<template id="cl-question-template">
    <div class="card mb-3 cl-question-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h5 class="card-title mb-0">Question <span class="cl-q-num"></span></h5>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.cl-question-card').remove()">
                    <i class="bi bi-trash"></i> Supprimer
                </button>
            </div>
            <div class="mb-3">
                <label class="form-label">Énoncé</label>
                <textarea class="form-control cl-q-enonce" rows="2" required></textarea>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Type</label>
                    <select class="form-select cl-q-type" onchange="clToggleOptions(this)">
                        <option value="qcm">QCM</option>
                        <option value="question">Question ouverte</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Temps de réponse (s)</label>
                    <input type="number" min="5" class="form-control cl-q-temps" value="60" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Barème (points)</label>
                    <input type="number" step="0.01" min="0" class="form-control cl-q-bareme" value="1" required>
                </div>
            </div>
            <div class="cl-q-options-bloc">
                <label class="form-label">Options de réponse</label>
                <div class="cl-q-options-container"></div>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="clAjouterOption(this)">+ Ajouter une option</button>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
let clQuestionIndex = 0;

function clAjouterQuestion() {
    const tpl = document.getElementById('cl-question-template');
    const clone = tpl.content.cloneNode(true);
    const card = clone.querySelector('.cl-question-card');
    card.dataset.qkey = 'q' + clQuestionIndex;
    document.getElementById('cl-questions-container').appendChild(clone);
    clQuestionIndex++;

    const nouvelleCarte = document.querySelector('.cl-question-card:last-child');
    clAjouterOption(nouvelleCarte.querySelector('.cl-q-options-bloc button'));
    clAjouterOption(nouvelleCarte.querySelector('.cl-q-options-bloc button'));
    clRenumeroter();
}

function clAjouterOption(bouton) {
    const bloc = bouton.closest('.cl-q-options-bloc');
    const container = bloc.querySelector('.cl-q-options-container');
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <div class="input-group-text"><input type="checkbox" class="cl-opt-correct" value="1"></div>
        <input type="text" class="form-control cl-opt-texte" placeholder="Texte de l'option">
        <button type="button" class="btn btn-outline-danger" onclick="this.closest('.input-group').remove()">✕</button>
    `;
    container.appendChild(div);
}

function clToggleOptions(select) {
    const bloc = select.closest('.cl-question-card').querySelector('.cl-q-options-bloc');
    bloc.style.display = select.value === 'qcm' ? 'block' : 'none';
}

function clRenumeroter() {
    document.querySelectorAll('.cl-question-card').forEach((card, i) => {
        card.querySelector('.cl-q-num').textContent = i + 1;
    });
}

document.querySelector('form').addEventListener('submit', function (e) {
    document.querySelectorAll('.cl-question-card').forEach((card, i) => {
        const prefix = `questions[q${i}]`;
        card.querySelector('.cl-q-enonce').name = `${prefix}[enonce]`;
        card.querySelector('.cl-q-type').name = `${prefix}[type_question]`;
        card.querySelector('.cl-q-temps').name = `${prefix}[temps_reponse]`;
        card.querySelector('.cl-q-bareme').name = `${prefix}[bareme]`;
        card.querySelectorAll('.cl-q-options-container .input-group').forEach((opt, j) => {
            opt.querySelector('.cl-opt-texte').name = `${prefix}[options][o${j}][texte]`;
            opt.querySelector('.cl-opt-correct').name = `${prefix}[options][o${j}][correct]`;
        });
    });
});

clAjouterQuestion();
</script>
@endpush
