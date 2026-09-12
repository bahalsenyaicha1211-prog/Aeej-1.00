<x-member-layout>
    <x-slot name="header">Enregistrer un paiement de cotisation</x-slot>

    <div class="card">
        <div class="section__head">
            <div class="section__title">Nouveau paiement</div>
            <a class="section__link" href="{{ route('tresorerie.cotisations.index') }}">← Retour</a>
        </div>
        <p style="color:var(--muted); font-size:13px; margin-top:-6px; margin-bottom:16px;">Sélectionnez le membre, le montant dû s'affiche automatiquement.</p>

        @if($errors->any())
            <div class="alert alert--danger">
                @foreach($errors->all() as $error)
                    <div>⚠️ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="cotTabs" role="tablist">
            <button type="button" class="cotTabs__btn is-active" data-cot-tab="annuelle">Cotisation annuelle</button>
            <button type="button" class="cotTabs__btn" data-cot-tab="volontaire">Cotisation volontaire</button>
        </div>

        <div data-cot-panel="annuelle">

        @if($configs->isEmpty())
            <div class="alert alert--warning">
                ⚠️ Aucun montant de cotisation n'a encore été configuré. Demandez au chef trésorier de le faire avant d'enregistrer un paiement.
            </div>
        @endif
        @if($dates->isEmpty())
            <div class="alert alert--warning">
                ⚠️ Aucune date de collecte n'a encore été configurée. Demandez au chef trésorier d'en ajouter (« Montants cotisation ») avant d'enregistrer un paiement.
            </div>
        @endif

        <form method="POST" action="{{ route('tresorerie.cotisations.store') }}" id="cotisation-form">
            @csrf

            <div class="grid grid-2">
                <div class="field">
                    <label>Membre *</label>
                    <select class="input" name="matricule" id="matricule" required>
                        <option value="">— Sélectionner un membre —</option>
                        @foreach($membres as $m)
                            <option value="{{ $m->matricule }}"
                                data-pays="{{ $m->pays->nom ?? '—' }}"
                                data-categorie="{{ in_array($m->matricule, $membresBureau) ? 'bureau' : 'membre' }}"
                                {{ old('matricule') === $m->matricule ? 'selected' : '' }}>
                                {{ $m->prenom }} {{ $m->nom }} — {{ $m->matricule }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>Année *</label>
                    <input class="input" type="number" name="annee" id="annee" value="{{ old('annee', now()->year) }}" min="2010" max="{{ now()->year + 1 }}" required>
                </div>

                <div class="field">
                    <label>Pays</label>
                    <input class="input" id="pays-affiche" value="—" disabled>
                </div>

                <div class="field">
                    <label>Catégorie</label>
                    <input class="input" id="categorie-affiche" value="—" disabled>
                </div>

                <div class="field">
                    <label>Montant à payer (TND)</label>
                    <input class="input" id="montant-du-affiche" value="—" disabled>
                </div>

                <div class="field">
                    <label>Montant payé (TND) *</label>
                    <input class="input" type="number" step="0.01" min="0" name="montant_paye" id="montant_paye" value="{{ old('montant_paye') }}" required>
                </div>

                <div class="field">
                    <label>Reste à payer (TND)</label>
                    <input class="input" id="reste-affiche" value="—" disabled style="font-weight:800; color:#d97706;">
                </div>

                <div class="field">
                    <label>Date du paiement *</label>
                    <select class="input" name="date_paiement" id="date_paiement" required>
                        <option value="">— Choisir une date de collecte —</option>
                    </select>
                    <span class="field__hint" id="date-paiement-hint" hidden>Aucune date de collecte configurée pour cette année.</span>
                </div>
            </div>

            <div style="margin-top:24px; display:flex; gap:12px; align-items:center;">
                <button class="btn btn--primary" type="submit">Enregistrer le paiement</button>
                <a href="{{ route('tresorerie.cotisations.index') }}" style="color:var(--muted); font-size:14px; font-weight:600;">Annuler</a>
            </div>
        </form>

        </div>

        <div data-cot-panel="volontaire" hidden>

            @if($typesVolontaires->isEmpty())
                <div class="alert alert--warning">
                    ⚠️ Aucune cotisation volontaire n'a encore été configurée. Ajoutez-en une depuis « Montants cotisation » (ex. Camping, montant fixe) avant d'enregistrer un paiement.
                </div>
            @endif

            <form method="POST" action="{{ route('tresorerie.cotisations-volontaires.store') }}">
                @csrf

                <div class="grid grid-2">
                    <div class="field">
                        <label>Membre *</label>
                        <select class="input" name="matricule" required>
                            <option value="">— Sélectionner un membre —</option>
                            @foreach($membres as $m)
                                <option value="{{ $m->matricule }}">{{ $m->prenom }} {{ $m->nom }} — {{ $m->matricule }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label>Cotisation volontaire *</label>
                        <select class="input" name="cotisation_type_id" id="cotisation_type_id" required @if($typesVolontaires->isEmpty()) disabled @endif>
                            <option value="">— Choisir —</option>
                            @foreach($typesVolontaires as $t)
                                <option value="{{ $t->id }}" data-montant="{{ $t->montant }}">{{ $t->nom }} ({{ $t->annee }}) — {{ number_format($t->montant, 2, ',', ' ') }} TND</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label>Montant payé (TND) *</label>
                        <input class="input" type="number" step="0.01" min="0" name="montant_paye" id="montant_paye_volontaire" required>
                    </div>

                    <div class="field">
                        <label>Date du paiement *</label>
                        <input class="input" type="date" name="date_paiement" value="{{ now()->toDateString() }}" required>
                    </div>
                </div>

                <div style="margin-top:24px; display:flex; gap:12px; align-items:center;">
                    <button class="btn btn--primary" type="submit" @if($typesVolontaires->isEmpty()) disabled @endif>Enregistrer le paiement</button>
                    <a href="{{ route('tresorerie.cotisations.index') }}" style="color:var(--muted); font-size:14px; font-weight:600;">Annuler</a>
                </div>
            </form>

        </div>
    </div>

<style>
.cotTabs{ display:flex; gap:8px; margin-bottom:18px; border-bottom:1px solid var(--border); }
.cotTabs__btn{
    padding:10px 16px; border:0; background:none; cursor:pointer;
    font-size:14px; font-weight:800; color:var(--muted);
    border-bottom:2px solid transparent; margin-bottom:-1px;
    transition: color .15s ease, border-color .15s ease;
}
.cotTabs__btn:hover{ color:var(--text); }
.cotTabs__btn.is-active{ color:var(--brand2); border-color:var(--brand2); }
</style>

<script>
(() => {
    const configs = @json($configs->keyBy('annee'));
    const datesParAnnee = @json($dates->map(fn ($liste) => $liste->pluck('date_collecte')->map->toDateString()));
    const matriculeSelect = document.getElementById('matricule');
    const anneeInput = document.getElementById('annee');
    const datePaiementSelect = document.getElementById('date_paiement');
    const datePaiementHint = document.getElementById('date-paiement-hint');
    const paysAffiche = document.getElementById('pays-affiche');
    const categorieAffiche = document.getElementById('categorie-affiche');
    const montantDuAffiche = document.getElementById('montant-du-affiche');
    const montantPayeInput = document.getElementById('montant_paye');
    const resteAffiche = document.getElementById('reste-affiche');

    function majDates() {
        const valeurActuelle = datePaiementSelect.value;
        const dates = datesParAnnee[anneeInput.value] || [];

        datePaiementSelect.innerHTML = '<option value="">— Choisir une date de collecte —</option>';
        dates.forEach((d) => {
            const opt = document.createElement('option');
            opt.value = d;
            opt.textContent = new Date(d + 'T00:00:00').toLocaleDateString('fr-FR');
            if (d === valeurActuelle) opt.selected = true;
            datePaiementSelect.appendChild(opt);
        });

        datePaiementSelect.disabled = dates.length === 0;
        datePaiementHint.hidden = dates.length !== 0;
        // Le select ayant été reconstruit, l'amélioration « liste moderne »
        // (select-search.js) doit se resynchroniser sur ses nouvelles options.
        datePaiementSelect.dispatchEvent(new Event('ssel:refresh'));
    }

    function montantDuActuel() {
        const option = matriculeSelect.selectedOptions[0];
        if (!option || !option.value) return null;

        const annee = configs[anneeInput.value];
        if (!annee) return null;

        const categorie = option.dataset.categorie;
        return categorie === 'bureau' ? parseFloat(annee.montant_bureau) : parseFloat(annee.montant_membre);
    }

    function maj() {
        const option = matriculeSelect.selectedOptions[0];

        if (!option || !option.value) {
            paysAffiche.value = '—';
            categorieAffiche.value = '—';
            montantDuAffiche.value = '—';
            resteAffiche.value = '—';
            return;
        }

        paysAffiche.value = option.dataset.pays;
        categorieAffiche.value = option.dataset.categorie === 'bureau' ? 'Membre du bureau' : 'Membre simple';

        const montantDu = montantDuActuel();
        if (montantDu === null) {
            montantDuAffiche.value = 'Non configuré pour ' + anneeInput.value;
            resteAffiche.value = '—';
            return;
        }

        montantDuAffiche.value = montantDu.toFixed(2) + ' TND';
        montantPayeInput.max = montantDu;

        const paye = parseFloat(montantPayeInput.value) || 0;
        const reste = Math.max(0, montantDu - paye);
        resteAffiche.value = reste.toFixed(2) + ' TND';
    }

    matriculeSelect.addEventListener('change', maj);
    anneeInput.addEventListener('input', maj);
    anneeInput.addEventListener('input', majDates);
    montantPayeInput.addEventListener('input', maj);

    maj();
    majDates();

    // ===== Onglets Annuelle / Volontaire =====
    const tabBtns = document.querySelectorAll('[data-cot-tab]');
    const panels = {
        annuelle: document.querySelector('[data-cot-panel="annuelle"]'),
        volontaire: document.querySelector('[data-cot-panel="volontaire"]'),
    };
    tabBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            const cible = btn.dataset.cotTab;
            tabBtns.forEach((b) => b.classList.toggle('is-active', b === btn));
            Object.entries(panels).forEach(([nom, panel]) => {
                if (panel) panel.hidden = nom !== cible;
            });
        });
    });

    // ===== Cotisation volontaire : pré-remplir le montant selon le motif choisi =====
    const typeSelect = document.getElementById('cotisation_type_id');
    const montantVolontaireInput = document.getElementById('montant_paye_volontaire');
    if (typeSelect && montantVolontaireInput) {
        typeSelect.addEventListener('change', () => {
            const option = typeSelect.selectedOptions[0];
            const montant = option ? parseFloat(option.dataset.montant) : NaN;
            if (!isNaN(montant)) montantVolontaireInput.value = montant.toFixed(2);
        });
    }
})();
</script>
</x-member-layout>
