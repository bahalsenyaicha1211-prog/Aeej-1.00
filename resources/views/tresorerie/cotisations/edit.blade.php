<x-member-layout>
    <x-slot name="header">Modifier un paiement de cotisation</x-slot>

    <div class="card">
        <div class="section__head">
            <div class="section__title">{{ $cotisation->membre->prenom ?? '' }} {{ $cotisation->membre->nom ?? '' }}</div>
            <a class="section__link" href="{{ route('tresorerie.cotisations.index') }}">← Retour</a>
        </div>
        <p style="color:var(--muted); font-size:13px; margin-top:-6px; margin-bottom:16px;">
            Cotisation {{ $cotisation->annee }} — {{ $cotisation->categorie === 'bureau' ? 'Membre du bureau' : 'Membre simple' }}
        </p>

        @if($errors->any())
            <div class="alert alert--danger">
                @foreach($errors->all() as $error)
                    <div>⚠️ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('tresorerie.cotisations.update', $cotisation) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2">
                <div class="field">
                    <label>Pays</label>
                    <input class="input" value="{{ $cotisation->membre->pays->nom ?? '—' }}" disabled>
                </div>

                <div class="field">
                    <label>Montant à payer (TND)</label>
                    <input class="input" value="{{ number_format($cotisation->montant_du, 2, ',', ' ') }}" disabled>
                </div>

                <div class="field">
                    <label>Montant payé (TND) *</label>
                    <input class="input" type="number" step="0.01" min="0" max="{{ $cotisation->montant_du }}" name="montant_paye" id="montant_paye" value="{{ old('montant_paye', $cotisation->montant_paye) }}" required>
                </div>

                <div class="field">
                    <label>Reste à payer (TND)</label>
                    <input class="input" id="reste-affiche" value="{{ number_format($cotisation->reste, 2, ',', ' ') }}" disabled style="font-weight:800; color:#d97706;">
                </div>

                <div class="field">
                    <label>Date du paiement *</label>
                    <input class="input" type="date" name="date_paiement" value="{{ old('date_paiement', $cotisation->date_paiement->toDateString()) }}" max="{{ now()->toDateString() }}" required>
                </div>
            </div>

            <div style="margin-top:24px; display:flex; gap:12px; align-items:center;">
                <button class="btn btn--primary" type="submit">Mettre à jour</button>
                <a href="{{ route('tresorerie.cotisations.index') }}" style="color:var(--muted); font-size:14px; font-weight:600;">Annuler</a>
            </div>
        </form>
    </div>

<script>
(() => {
    const montantDu = {{ (float) $cotisation->montant_du }};
    const montantPayeInput = document.getElementById('montant_paye');
    const resteAffiche = document.getElementById('reste-affiche');

    function maj() {
        const paye = parseFloat(montantPayeInput.value) || 0;
        const reste = Math.max(0, montantDu - paye);
        resteAffiche.value = reste.toFixed(2) + ' TND';
    }

    montantPayeInput.addEventListener('input', maj);
})();
</script>
</x-member-layout>
