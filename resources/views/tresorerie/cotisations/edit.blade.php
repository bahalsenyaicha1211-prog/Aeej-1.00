@extends('layouts.tresorerie')

@section('title', 'Trésorerie • Modifier paiement')
@section('header', 'Modifier un paiement de cotisation')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">{{ $cotisation->membre->prenom ?? '' }} {{ $cotisation->membre->nom ?? '' }}</h1>
            <p class="admDash__sub">Cotisation {{ $cotisation->annee }} — {{ $cotisation->categorie === 'bureau' ? 'Membre du bureau' : 'Membre simple' }}</p>
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

    <div class="admGrid">
        <div class="admPanel admPanel--full">
            <div class="admPanel__body">
                <form class="admRows" method="POST" action="{{ route('tresorerie.cotisations.update', $cotisation) }}">
                    @csrf
                    @method('PUT')

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="field">
                            <label class="admKpi__label text-white">Pays</label>
                            <input class="input" value="{{ $cotisation->membre->pays->nom ?? '—' }}" disabled style="width:100%; opacity:0.7;">
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Montant à payer (TND)</label>
                            <input class="input" value="{{ number_format($cotisation->montant_du, 2, ',', ' ') }}" disabled style="width:100%; opacity:0.7;">
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Montant payé (TND) *</label>
                            <input class="input" type="number" step="0.01" min="0" max="{{ $cotisation->montant_du }}" name="montant_paye" id="montant_paye" value="{{ old('montant_paye', $cotisation->montant_paye) }}" required style="width:100%;">
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Reste à payer (TND)</label>
                            <input class="input" id="reste-affiche" value="{{ number_format($cotisation->reste, 2, ',', ' ') }}" disabled style="width:100%; opacity:0.7; font-weight:800; color:#fbbf24;">
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Date du paiement *</label>
                            <input class="input" type="date" name="date_paiement" value="{{ old('date_paiement', $cotisation->date_paiement->toDateString()) }}" max="{{ now()->toDateString() }}" required style="width:100%;">
                        </div>
                    </div>

                    <div style="margin-top: 30px; display: flex; gap: 12px; align-items: center;">
                        <button class="btn" style="background: #3b82f6; color: #fff; border-radius: 12px; padding: 12px 30px; font-weight: 800; border: none; cursor: pointer;" type="submit">
                            Mettre à jour
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
@endsection
