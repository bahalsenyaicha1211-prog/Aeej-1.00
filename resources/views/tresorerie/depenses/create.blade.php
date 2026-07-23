<x-member-layout>
    <x-slot name="header">Enregistrer une dépense</x-slot>

    <div class="card">
        <div class="section__head">
            <div class="section__title">Nouvelle dépense</div>
            <a class="section__link" href="{{ route('tresorerie.depenses.index') }}">← Retour</a>
        </div>
        <p style="color:var(--muted); font-size:13px; margin-top:-6px; margin-bottom:16px;">
            Ajoutez une ou plusieurs lignes (désignation + montant), le total est calculé automatiquement.
        </p>

        @if($errors->any())
            <div class="alert alert--danger">
                @foreach($errors->all() as $error)
                    <div>⚠️ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('tresorerie.depenses.store') }}" id="depense-form">
            @csrf

            <div class="grid grid-2">
                <div class="field">
                    <label>Nom de l'événement *</label>
                    <input class="input" name="nom_evenement" value="{{ old('nom_evenement') }}" required placeholder="ex: Journée culturelle AEEJ">
                </div>
                <div class="field">
                    <label>Date de la dépense *</label>
                    <input class="input" type="date" name="date_depense" value="{{ old('date_depense', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required>
                </div>
            </div>

            <div style="margin-top:20px;">
                <label style="display:block; font-size:12px; font-weight:700; color:var(--muted); text-transform:uppercase; margin-bottom:10px;">Lignes de dépense *</label>
                <div id="lignes-container"></div>
                <button type="button" id="ajouter-ligne" class="btn btn--ghost" style="margin-top:10px;">+ Ajouter une ligne</button>
            </div>

            <div style="margin-top:20px; text-align:right;">
                <span style="color:var(--muted); font-size:13px; font-weight:700; text-transform:uppercase;">Total : </span>
                <span id="total-affiche" style="font-size:22px; font-weight:900; color:#dc2626;">0.00 TND</span>
            </div>

            <div style="margin-top:24px; display:flex; gap:12px; align-items:center;">
                <button class="btn btn--primary" type="submit">Enregistrer la dépense</button>
                <a href="{{ route('tresorerie.depenses.index') }}" style="color:var(--muted); font-size:14px; font-weight:600;">Annuler</a>
            </div>
        </form>
    </div>

<template id="ligne-template">
    <div class="ligne-depense" style="display:grid; grid-template-columns: 2fr 1fr auto; gap:12px; margin-bottom:10px; align-items:center;">
        <input class="input" type="text" placeholder="Désignation (ex: Location salle)" data-role="designation" required>
        <input class="input" type="number" step="0.01" min="0.01" placeholder="Montant" data-role="montant" required>
        <button type="button" class="btn supprimer-ligne" style="background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; padding:10px 14px;">✕</button>
    </div>
</template>

<script>
(() => {
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

    function ajouterLigne() {
        const node = template.content.cloneNode(true);
        const designationInput = node.querySelector('[data-role="designation"]');
        const montantInput = node.querySelector('[data-role="montant"]');

        designationInput.name = `lignes[${index}][designation]`;
        montantInput.name = `lignes[${index}][montant]`;
        index++;

        montantInput.addEventListener('input', recalculerTotal);
        node.querySelector('.supprimer-ligne').addEventListener('click', (e) => {
            e.target.closest('.ligne-depense').remove();
            recalculerTotal();
        });

        container.appendChild(node);
    }

    document.getElementById('ajouter-ligne').addEventListener('click', ajouterLigne);

    form.addEventListener('submit', (e) => {
        if (container.querySelectorAll('.ligne-depense').length === 0) {
            e.preventDefault();
            alert('Ajoutez au moins une ligne de dépense.');
        }
    });

    // Une première ligne par défaut
    ajouterLigne();
})();
</script>
</x-member-layout>
