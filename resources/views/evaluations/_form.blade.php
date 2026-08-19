<div class="mb-3">
    <label class="form-label">Titre</label>
    <input type="text" name="titre" value="{{ old('titre', $evaluation->titre ?? '') }}" class="form-control @error('titre') is-invalid @enderror">
    @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Type</label>
        <select name="type_evaluation" id="type_evaluation" class="form-select @error('type_evaluation') is-invalid @enderror" onchange="toggleChapitre(this.value)">
            <option value="test_chapitre" @selected(old('type_evaluation', $evaluation->type_evaluation ?? '') === 'test_chapitre')>Test de chapitre</option>
            <option value="examen_final" @selected(old('type_evaluation', $evaluation->type_evaluation ?? '') === 'examen_final')>Examen final</option>
        </select>
        @error('type_evaluation') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3" id="bloc-chapitre">
        <label class="form-label">Chapitre</label>
        <select name="chapitre_id" class="form-select @error('chapitre_id') is-invalid @enderror">
            <option value="">—</option>
            @foreach ($chapitres as $chapitre)
                <option value="{{ $chapitre->id }}" @selected(old('chapitre_id', $evaluation->chapitre_id ?? '') == $chapitre->id)>{{ $chapitre->titre }}</option>
            @endforeach
        </select>
        @error('chapitre_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Seuil de réussite (%)</label>
        <input type="number" step="0.01" min="0" max="100" name="seuil_reussite"
               value="{{ old('seuil_reussite', $evaluation->seuil_reussite ?? '') }}"
               placeholder="85 pour un chapitre, 80 pour un examen final"
               class="form-control @error('seuil_reussite') is-invalid @enderror">
        @error('seuil_reussite') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Durée maximale (minutes)</label>
        <input type="number" min="1" name="duree_max" value="{{ old('duree_max', $evaluation->duree_max ?? '') }}" class="form-control @error('duree_max') is-invalid @enderror">
        @error('duree_max') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<script>
function toggleChapitre(type) {
    document.getElementById('bloc-chapitre').style.display = type === 'examen_final' ? 'none' : 'block';
}
toggleChapitre(document.getElementById('type_evaluation').value);
</script>
