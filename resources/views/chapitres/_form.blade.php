<div class="mb-3">
    <label class="form-label">Titre</label>
    <input type="text" name="titre" value="{{ old('titre', $chapitre->titre ?? '') }}" class="form-control @error('titre') is-invalid @enderror">
    @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <label class="form-label mb-0">Contenu</label>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clPreview()"><i class="bi bi-eye"></i> Prévisualiser</button>
    </div>
    <textarea name="contenu" id="contenu-editor" class="form-control @error('contenu') is-invalid @enderror">{{ old('contenu', $chapitre->contenu ?? '') }}</textarea>
    @error('contenu') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="modal fade" id="clPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aperçu du chapitre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="clPreviewBody"></div>
        </div>
    </div>
</div>
@push('scripts')

<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

<script>
let clEditorInstance = null;

document.addEventListener('DOMContentLoaded', function () {

    const editorElement = document.querySelector('#contenu-editor');

    if (!editorElement) {
        console.error('Textarea #contenu-editor introuvable');
        return;
    }

    ClassicEditor
        .create(editorElement)
        .then(editor => {
            clEditorInstance = editor;
            console.log('CKEditor chargé');
        })
        .catch(error => {
            console.error(error);
        });

});

function clPreview() {

    let contenu = '';

    if (clEditorInstance) {
        contenu = clEditorInstance.getData();
    } else {
        contenu = document.getElementById('contenu-editor').value;
    }

    document.getElementById('clPreviewBody').innerHTML =
        contenu || '<p class="text-muted">Aucun contenu.</p>';

    new bootstrap.Modal(
        document.getElementById('clPreviewModal')
    ).show();
}
</script>

@endpush