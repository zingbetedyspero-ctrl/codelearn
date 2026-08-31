@push('styles')
<style>
    .cl-auth-wrap { min-height: calc(100vh - 300px); display: flex; align-items: center; justify-content: center; padding: var(--cl-space-16) var(--cl-space-4); }
    .cl-auth-card { width: 100%; max-width: 460px; background: var(--cl-blanc); border: 1px solid var(--cl-gris-200); border-radius: var(--cl-radius-lg); box-shadow: var(--cl-shadow-lg); padding: var(--cl-space-8); animation: cl-rise 0.4s cubic-bezier(0.4,0,0.2,1); }
    @keyframes cl-rise { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
    .cl-tabs { display: flex; background: var(--cl-gris-100); border-radius: var(--cl-radius-full); padding: 4px; margin-bottom: var(--cl-space-8); }
    .cl-tab { flex: 1; text-align: center; padding: 0.6rem 1rem; border-radius: var(--cl-radius-full); font-weight: 600; color: var(--cl-gris-500); cursor: pointer; border: none; background: none; transition: all var(--cl-transition-base); }
    .cl-tab.active { background: var(--cl-blanc); color: var(--cl-bleu); box-shadow: var(--cl-shadow-sm); }
    .cl-field { position: relative; margin-bottom: var(--cl-space-4); }
    .cl-field .cl-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--cl-gris-500); pointer-events: none; }
    .cl-field input { padding-left: 2.6rem; }
    .cl-field .cl-check { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); font-size: 1.1rem; display: none; }
    .cl-field.valide .cl-check.ok { display: inline; color: var(--cl-vert); }
    .cl-field.invalide .cl-check.ko { display: inline; color: #DC2626; }
    .cl-panel { display: none; animation: cl-fade 0.25s ease; }
    .cl-panel.active { display: block; }
    @keyframes cl-fade { from { opacity: 0; } to { opacity: 1; } }
</style>
@endpush

<div class="cl-auth-wrap">
    <div class="cl-auth-card">
        <div class="cl-tabs">
            <button type="button" class="cl-tab {{ $actif === 'login' ? 'active' : '' }}" onclick="clSwitchTab('login')">Connexion</button>
            <button type="button" class="cl-tab {{ $actif === 'register' ? 'active' : '' }}" onclick="clSwitchTab('register')">Inscription</button>
        </div>

        <div id="panel-login" class="cl-panel {{ $actif === 'login' ? 'active' : '' }}">
            <h3 class="mb-1">Content de vous revoir</h3>
            <p class="text-muted mb-4">Connectez-vous pour continuer votre apprentissage.</p>
            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf
                <div class="cl-field" data-type="email">
                    <span class="cl-icon"></span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Adresse email"
                           class="form-control @error('email') is-invalid @enderror" oninput="clValiderChamp(this)">
                    <span class="cl-check ok">✓</span><span class="cl-check ko">✕</span>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="cl-field" data-type="required">
                    <span class="cl-icon"></span>
                    <input type="password" name="password" placeholder="Mot de passe"
                           class="form-control @error('password') is-invalid @enderror" oninput="clValiderChamp(this)">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label small" for="remember">Se souvenir de moi</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>
        </div>

        <div id="panel-register" class="cl-panel {{ $actif === 'register' ? 'active' : '' }}">
            <h3 class="mb-1">Créer votre compte</h3>
            <p class="text-muted mb-4">Gratuit — commencez par l'introduction de n'importe quel cours.</p>
            <form method="POST" action="{{ route('register') }}" novalidate>
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="cl-field" data-type="required">
                            <span class="cl-icon"></span>
                            <input type="text" name="prenom" value="{{ old('prenom') }}" placeholder="Prénom"
                                   class="form-control @error('prenom') is-invalid @enderror" oninput="clValiderChamp(this)">
                            <span class="cl-check ok">✓</span><span class="cl-check ko">✕</span>
                            @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="cl-field" data-type="required">
                            <span class="cl-icon"></span>
                            <input type="text" name="nom" value="{{ old('nom') }}" placeholder="Nom"
                                   class="form-control @error('nom') is-invalid @enderror" oninput="clValiderChamp(this)">
                            <span class="cl-check ok">✓</span><span class="cl-check ko">✕</span>
                            @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="cl-field" data-type="email">
                    <span class="cl-icon"></span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Adresse email"
                           class="form-control @error('email') is-invalid @enderror" oninput="clValiderChamp(this)">
                    <span class="cl-check ok">✓</span><span class="cl-check ko">✕</span>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="cl-field" data-type="required">
                    <span class="cl-icon"></span>
                    <input type="tel" name="telephone" value="{{ old('telephone') }}" placeholder="Numéro de téléphone"
                           class="form-control @error('telephone') is-invalid @enderror" oninput="clValiderChamp(this)">
                    <span class="cl-check ok">✓</span><span class="cl-check ko">✕</span>
                    @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="cl-field" data-type="password">
                    <span class="cl-icon"></span>
                    <input type="password" name="password" placeholder="Mot de passe"
                           class="form-control @error('password') is-invalid @enderror" oninput="clValiderChamp(this); clVerifierCriteres(this.value);">
                    <span class="cl-check ok">✓</span><span class="cl-check ko">✕</span>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <ul class="small text-muted mb-3" id="clCriteresListe" style="list-style:none; padding-left:0;">
                    <li id="cl-crit-longueur"><i class="bi bi-circle"></i> Au moins 8 caractères</li>
                    <li id="cl-crit-majuscule"><i class="bi bi-circle"></i> Une lettre majuscule</li>
                    <li id="cl-crit-minuscule"><i class="bi bi-circle"></i> Une lettre minuscule</li>
                    <li id="cl-crit-chiffre"><i class="bi bi-circle"></i> Un chiffre</li>
                    <li id="cl-crit-special"><i class="bi bi-circle"></i> Un caractère spécial (@#$%!?&...)</li>
                </ul>
                <div class="cl-field" data-type="required">
                    <span class="cl-icon"></span>
                    <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe"
                           class="form-control" oninput="clValiderChamp(this)">
                </div>
                <button type="submit" class="btn btn-cta w-100 mt-2">Créer mon compte</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function clSwitchTab(nom) {
        document.querySelectorAll('.cl-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.cl-panel').forEach(p => p.classList.remove('active'));
        event.currentTarget.classList.add('active');
        document.getElementById('panel-' + nom).classList.add('active');
    }
    function clCriteresMotDePasse(valeur) {
        return {
            longueur: valeur.length >= 8,
            majuscule: /[A-Z]/.test(valeur),
            minuscule: /[a-z]/.test(valeur),
            chiffre: /[0-9]/.test(valeur),
            special: /[^A-Za-z0-9]/.test(valeur),
        };
    }

    function clValiderChamp(input) {
        const wrapper = input.closest('.cl-field');
        const type = wrapper.dataset.type;
        let valide = false;
        if (type === 'email') { valide = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value); }
        else if (type === 'password') {
            const c = clCriteresMotDePasse(input.value);
            valide = c.longueur && c.majuscule && c.minuscule && c.chiffre && c.special;
        }
        else { valide = input.value.trim().length > 0; }
        wrapper.classList.toggle('valide', valide && input.value.length > 0);
        wrapper.classList.toggle('invalide', !valide && input.value.length > 0);
    }

    function clVerifierCriteres(valeur) {
        const c = clCriteresMotDePasse(valeur);
        const mapping = { longueur: 'cl-crit-longueur', majuscule: 'cl-crit-majuscule', minuscule: 'cl-crit-minuscule', chiffre: 'cl-crit-chiffre', special: 'cl-crit-special' };
        Object.keys(mapping).forEach(function (cle) {
            const li = document.getElementById(mapping[cle]);
            if (!li) return;
            const icone = li.querySelector('i');
            if (c[cle]) {
                icone.className = 'bi bi-check-circle-fill text-success';
                li.style.color = 'var(--cl-vert)';
            } else {
                icone.className = 'bi bi-circle';
                li.style.color = '';
            }
        });
    }
</script>
@endpush
