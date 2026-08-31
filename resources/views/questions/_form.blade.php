<div class="mb-3">
    <label class="form-label">Énoncé</label>
    <textarea name="enonce" rows="3" class="form-control @error('enonce') is-invalid @enderror">{{ old('enonce', $question->enonce ?? '') }}</textarea>
    @error('enonce') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Type</label>
        <select name="type_question" id="type_question" class="form-select" onchange="toggleOptions(this.value)">
            <option value="qcm" @selected(old('type_question', $question->type_question ?? '') === 'qcm')>QCM</option>
            <option value="question" @selected(old('type_question', $question->type_question ?? '') === 'question')>Question ouverte</option>
        </select>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Temps de réponse (secondes)</label>
        <input type="number" min="5" name="temps_reponse" value="{{ old('temps_reponse', $question->temps_reponse ?? '') }}" class="form-control @error('temps_reponse') is-invalid @enderror">
        @error('temps_reponse') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="form-text">RG07 : quelques dizaines de secondes pour un QCM, plusieurs minutes pour une question ouverte.</div>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Barème (points)</label>
        <input type="number" step="0.01" min="0" name="bareme" value="{{ old('bareme', $question->bareme ?? '') }}" class="form-control @error('bareme') is-invalid @enderror">
        @error('bareme') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div id="bloc-options" class="mb-3">
    <label class="form-label">Options de réponse</label>
    <div id="options-container">
        @php $optionsExistantes = $question->optionsReponse ?? collect(); @endphp
        @forelse ($optionsExistantes as $option)
            <div class="input-group mb-2">
                <div class="input-group-text">
                    <input type="checkbox" name="options[opt-{{ $option->id }}][correct]" value="1" {{ $option->is_correct ? 'checked' : '' }}>
                </div>
                <input type="text" name="options[opt-{{ $option->id }}][texte]" value="{{ $option->option_texte }}" class="form-control" placeholder="Texte de l'option">
                <button type="button" class="btn btn-outline-danger" onclick="this.closest('.input-group').remove()">✕</button>
            </div>
        @empty
            @for ($i = 0; $i < 2; $i++)
                <div class="input-group mb-2">
                    <div class="input-group-text">
                        <input type="checkbox" name="options[new-{{ $i }}][correct]" value="1">
                    </div>
                    <input type="text" name="options[new-{{ $i }}][texte]" class="form-control" placeholder="Texte de l'option">
                    <button type="button" class="btn btn-outline-danger" onclick="this.closest('.input-group').remove()">✕</button>
                </div>
            @endfor
        @endforelse
    </div>
    <button type="button" class="btn btn-sm btn-outline-primary" onclick="ajouterOption()">+ Ajouter une option</button>
    <div class="form-text">Coche la case devant la ou les bonnes réponses.</div>
</div>
<script>
function genererCle() { return 'new-' + Date.now() + '-' + Math.floor(Math.random() * 10000); }
function ajouterOption() {
    const container = document.getElementById('options-container');
    const cle = genererCle();
    const div = document.createElement('div');
    div.className = 'input-group mb-2';
    div.innerHTML = `
        <div class="input-group-text"><input type="checkbox" name="options[${cle}][correct]" value="1"></div>
        <input type="text" name="options[${cle}][texte]" class="form-control" placeholder="Texte de l'option">
        <button type="button" class="btn btn-outline-danger" onclick="this.closest('.input-group').remove()">✕</button>
    `;
    container.appendChild(div);
}
function toggleOptions(type) {
    document.getElementById('bloc-options').style.display = type === 'qcm' ? 'block' : 'none';
}
toggleOptions(document.getElementById('type_question').value);
</script>
