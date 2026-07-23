@extends('layouts.tresorerie')

@section('title', 'Trésorerie • Modifier dépense')
@section('header', 'Modifier une dépense')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">{{ $depense->nom_evenement }}</h1>
            <p class="admDash__sub">Modifiez les informations et les lignes de dépense.</p>
        </div>
        <a class="admQuick__btn" href="{{ route('tresorerie.depenses.index') }}" style="text-decoration: none;">← Retour</a>
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
                <form method="POST" action="{{ route('tresorerie.depenses.update', $depense) }}" id="depense-form">
                    @csrf
                    @method('PUT')

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                        <div class="field">
                            <label class="admKpi__label text-white">Nom de l'événement *</label>
                            <input class="input" name="nom_evenement" value="{{ old('nom_evenement', $depense->nom_evenement) }}" required style="width:100%;">
                        </div>
                        <div class="field">
                            <label class="admKpi__label text-white">Date de la dépense *</label>
                            <input class="input" type="date" name="date_depense" value="{{ old('date_depense', $depense->date_depense->toDateString()) }}" max="{{ now()->toDateString() }}" required style="width:100%;">
                        </div>
                    </div>

                    <div style="margin-top: 24px;">
                        <label class="admKpi__label text-white" style="display:block; margin-bottom:10px;">Lignes de dépense *</label>
                        <div id="lignes-container"></div>
                        <button type="button" id="ajouter-ligne" class="admQuick__btn" style="margin-top:10px;">+ Ajouter une ligne</button>
                    </div>

                    <div style="margin-top: 24px; text-align:right;">
                        <span style="color:#64748b; font-size:13px; font-weight:700; text-transform:uppercase;">Total : </span>
                        <span id="total-affiche" style="font-size:24px; font-weight:900; color:#f87171;">0.00 TND</span>
                    </div>

                    <div style="margin-top: 30px; display: flex; gap: 12px; align-items: center;">
                        <button class="btn" style="background: #3b82f6; color: #fff; border-radius: 12px; padding: 12px 30px; font-weight: 800; border: none; cursor: pointer;" type="submit">
                            Mettre à jour
                        </button>
                        <a href="{{ route('tresorerie.depenses.index') }}" style="color: #64748b; font-size: 14px; text-decoration: none; font-weight: 600;">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<template id="ligne-template">
    <div class="ligne-depense" style="display:grid; grid-template-columns: 2fr 1fr auto; gap:12px; margin-bottom:10px; align-items:center;">
        <input class="input" type="text" placeholder="Désignation (ex: Location salle)" data-role="designation" required style="width:100%;">
        <input class="input" type="number" step="0.01" min="0.01" placeholder="Montant" data-role="montant" required style="width:100%;">
        <button type="button" class="admQuick__btn supprimer-ligne" style="border-color: rgba(239,68,68,0.3); color: #f87171;">✕</button>
    </div>
</template>

<script>
(() => {
    const lignesExistantes = @json($depense->lignes->map(fn($l) => ['designation' => $l->designation, 'montant' => $l->montant]));

    const container = document.getElementById('lignes-container');
    const template = document.getElementById('ligne-template');
    const form = document.getElementById('depense-form');
    const totalAffiche = document.getElementById('total-affiche');
    let index = 0;

    function recalculerTotal() {
        let total = 0;
        container.querySelectorAll('.ligne-depense').forEach(ligne => {
            const montant = parseFloat(ligne.querySelector('[data-role="montant"]').value) || 0;
            total += montant;
        });
        totalAffiche.textContent = total.toFixed(2) + ' TND';
    }

    function ajouterLigne(valeurs) {
        const node = template.content.cloneNode(true);
        const designationInput = node.querySelector('[data-role="designation"]');
        const montantInput = node.querySelector('[data-role="montant"]');

        designationInput.name = `lignes[${index}][designation]`;
        montantInput.name = `lignes[${index}][montant]`;

        if (valeurs) {
            designationInput.value = valeurs.designation;
            montantInput.value = valeurs.montant;
        }

        index++;

        montantInput.addEventListener('input', recalculerTotal);
        node.querySelector('.supprimer-ligne').addEventListener('click', (e) => {
            e.target.closest('.ligne-depense').remove();
            recalculerTotal();
        });

        container.appendChild(node);
    }

    document.getElementById('ajouter-ligne').addEventListener('click', () => ajouterLigne());

    form.addEventListener('submit', (e) => {
        if (container.querySelectorAll('.ligne-depense').length === 0) {
            e.preventDefault();
            alert('Ajoutez au moins une ligne de dépense.');
        }
    });

    if (lignesExistantes.length > 0) {
        lignesExistantes.forEach(l => ajouterLigne(l));
    } else {
        ajouterLigne();
    }
    recalculerTotal();
})();
</script>
@endsection
