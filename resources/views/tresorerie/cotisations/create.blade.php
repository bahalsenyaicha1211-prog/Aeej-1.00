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

        @if($configs->isEmpty())
            <div class="alert alert--warning">
                ⚠️ Aucun montant de cotisation n'a encore été configuré. Demandez au chef trésorier de le faire avant d'enregistrer un paiement.
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
                    <input class="input" type="date" name="date_paiement" value="{{ old('date_paiement', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required>
                </div>
            </div>

            <div style="margin-top:24px; display:flex; gap:12px; align-items:center;">
                <button class="btn btn--primary" type="submit">Enregistrer le paiement</button>
                <a href="{{ route('tresorerie.cotisations.index') }}" style="color:var(--muted); font-size:14px; font-weight:600;">Annuler</a>
            </div>
        </form>
    </div>

<script>
(() => {
    const configs = @json($configs->keyBy('annee'));
    const matriculeSelect = document.getElementById('matricule');
    const anneeInput = document.getElementById('annee');
    const paysAffiche = document.getElementById('pays-affiche');
    const categorieAffiche = document.getElementById('categorie-affiche');
    const montantDuAffiche = document.getElementById('montant-du-affiche');
    const montantPayeInput = document.getElementById('montant_paye');
    const resteAffiche = document.getElementById('reste-affiche');

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
    montantPayeInput.addEventListener('input', maj);

    maj();
})();
</script>
</x-member-layout>
