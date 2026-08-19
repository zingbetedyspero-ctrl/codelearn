<div class="mb-3">
    <label class="form-label">Titre</label>
    <input type="text" name="titre" value="{{ old('titre', $cour->titre ?? '') }}" class="form-control @error('titre') is-invalid @enderror">
    @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $cour->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Niveau</label>
        <select name="niveau" class="form-select @error('niveau') is-invalid @enderror">
            @foreach (['debutant' => 'Débutant', 'intermediaire' => 'Intermédiaire', 'avance' => 'Avancé'] as $val => $label)
                <option value="{{ $val }}" @selected(old('niveau', $cour->niveau ?? 'debutant') === $val)>{{ $label }}</option>
            @endforeach
        </select>
        @error('niveau') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Prix (FCFA)</label>
        <input type="number" step="1" min="0" name="prix" value="{{ old('prix', $cour->prix ?? '') }}" class="form-control @error('prix') is-invalid @enderror">
        @error('prix') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Statut</label>
        <select name="statut" class="form-select @error('statut') is-invalid @enderror">
            <option value="non_publie" @selected(old('statut', $cour->statut ?? 'non_publie') === 'non_publie')>Non publié</option>
            <option value="publie" @selected(old('statut', $cour->statut ?? '') === 'publie')>Publié</option>
        </select>
        @error('statut') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Catégorie</label>
    <div class="input-group">
        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
            <option value="">— Aucune —</option>
            @foreach ($categories as $categorie)
                <option value="{{ $categorie->id }}" @selected(old('category_id', $cour->category_id ?? '') == $categorie->id)>{{ $categorie->nom }}</option>
            @endforeach
        </select>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#clNouvelleCategorie" title="Créer une nouvelle catégorie">
            <i class="bi bi-plus-lg"></i>
        </button>
    </div>
    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="modal fade" id="clNouvelleCategorie" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouvelle catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" id="clCatNom" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea id="clCatDescription" rows="2" class="form-control"></textarea>
                </div>
                <div id="clCatErreur" class="text-danger small"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="clCreerCategorie()">Créer</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function clCreerCategorie() {
    const nom = document.getElementById('clCatNom').value.trim();
    const description = document.getElementById('clCatDescription').value.trim();
    const erreurBox = document.getElementById('clCatErreur');
    erreurBox.textContent = '';
    if (!nom) { erreurBox.textContent = 'Le nom est obligatoire.'; return; }

    fetch("{{ route('categories.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ nom, description }),
    })
    .then(async (res) => {
        const data = await res.json();
        if (!res.ok) { erreurBox.textContent = data.message || 'Erreur lors de la création.'; return; }
        const select = document.getElementById('category_id');
        const option = document.createElement('option');
        option.value = data.categorie.id;
        option.textContent = data.categorie.nom;
        option.selected = true;
        select.appendChild(option);
        document.getElementById('clCatNom').value = '';
        document.getElementById('clCatDescription').value = '';
        bootstrap.Modal.getInstance(document.getElementById('clNouvelleCategorie')).hide();
    })
    .catch(() => { erreurBox.textContent = 'Erreur réseau.'; });
}
</script>
@endpush
<div class="mb-3">
    <label class="form-label">Image de couverture</label>
    <input type="file" name="image_couverture" class="form-control @error('image_couverture') is-invalid @enderror">
    @error('image_couverture') <div class="invalid-feedback">{{ $message }}</div> @enderror
    @if (!empty($cour) && $cour->image_couverture)
        <img src="{{ Storage::url($cour->image_couverture) }}" class="mt-2" style="width:120px;height:80px;object-fit:cover;">
    @endif
</div>
