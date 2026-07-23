@extends('layouts.tresorerie')

@section('title', 'Trésorerie • Nouveau paiement')
@section('header', 'Enregistrer un paiement de cotisation')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Nouveau paiement</h1>
            <p class="admDash__sub">Sélectionnez le membre, le montant dû s'affiche automatiquement.</p>
        </div>
        <a class="admQuick__btn" href="{{ route('tresorerie.cotisations.index') }}" style="text-decoration: none;">← Retour</a>
    </div>

    @if($errors->any())
        <div class="admPanel admPanel--full" style="border-color: rgba(239,68,68,0.3); background: rgba(239,68,68,0.05); margin-bottom: 16px;">
            <div class="admPanel__body">
                @foreach($errors->all() as $error)
                    <div style="color:#fb7185; font-size: 13px; font-weight: 600;">⚠️ {{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    @if($configs->isEmpty())
        <div class="admPanel admPanel--full" style="border-color: rgba(251,191,36,0.3); background: rgba(251,191,36,0.05); margin-bottom: 16px;">
            <div class="admPanel__body">
                <div style="color:#fbbf24; font-size: 13px; font-weight: 600;">⚠️ Aucun montant de cotisation n'a encore été configuré. Demandez au chef trésorier de le faire avant d'enregistrer un paiement.</div>
            </div>
        </div>
    @endif

    <div class="admGrid">
        <div class="admPanel admPanel--full">
            <div class="admPanel__body">
                <form class="admRows" method="POST" action="{{ route('tresorerie.cotisations.store') }}" id="cotisation-form">
                    @csrf

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="field">
                            <label class="admKpi__label text-white">Membre *</label>
                            <select class="input" name="matricule" id="matricule" required style="width:100%;">
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
                            <label class="admKpi__label text-white">Année *</label>
                            <input class="input" type="number" name="annee" id="annee" value="{{ old('annee', now()->year) }}" min="2010" max="{{ now()->year + 1 }}" required style="width:100%;">
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Pays</label>
                            <input class="input" id="pays-affiche" value="—" disabled style="width:100%; opacity:0.7;">
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Catégorie</label>
                            <input class="input" id="categorie-affiche" value="—" disabled style="width:100%; opacity:0.7;">
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Montant à payer (TND)</label>
                            <input class="input" id="montant-du-affiche" value="—" disabled style="width:100%; opacity:0.7;">
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Montant payé (TND) *</label>
                            <input class="input" type="number" step="0.01" min="0" name="montant_paye" id="montant_paye" value="{{ old('montant_paye') }}" required style="width:100%;">
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Reste à payer (TND)</label>
                            <input class="input" id="reste-affiche" value="—" disabled style="width:100%; opacity:0.7; font-weight:800; color:#fbbf24;">
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Date du paiement *</label>
                            <input class="input" type="date" name="date_paiement" value="{{ old('date_paiement', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required style="width:100%;">
                        </div>
                    </div>

                    <div style="margin-top: 30px; display: flex; gap: 12px; align-items: center;">
                        <button class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 12px 30px; font-weight: 800; border: none; cursor: pointer;" type="submit">
                            Enregistrer le paiement
                        </button>
                        <a href="{{ route('tresorerie.cotisations.index') }}" style="color: #64748b; font-size: 14px; text-decoration: none; font-weight: 600;">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
@endsection
