<x-member-layout>
    <x-slot name="header">Modifier un paiement volontaire</x-slot>

    <div class="card">
        <div class="section__head">
            <div class="section__title">{{ $volontaire->membre->prenom ?? '' }} {{ $volontaire->membre->nom ?? '' }}</div>
            <a class="section__link" href="{{ route('tresorerie.cotisations.index', ['tab' => 'volontaire']) }}">← Retour</a>
        </div>
        <p style="color:var(--muted); font-size:13px; margin-top:-6px; margin-bottom:16px;">
            {{ $volontaire->type->nom }} ({{ $volontaire->type->annee }})
        </p>

        @if($errors->any())
            <div class="alert alert--danger">
                @foreach($errors->all() as $error)
                    <div>⚠️ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('tresorerie.cotisations-volontaires.update', $volontaire) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-2">
                <div class="field">
                    <label>Pays</label>
                    <input class="input" value="{{ $volontaire->membre->pays->nom ?? '—' }}" disabled>
                </div>

                <div class="field">
                    <label>Montant de référence (TND)</label>
                    <input class="input" value="{{ number_format($volontaire->type->montant, 2, ',', ' ') }}" disabled>
                </div>

                <div class="field">
                    <label>Montant payé (TND) *</label>
                    <input class="input" type="number" step="0.01" min="0" name="montant_paye" value="{{ old('montant_paye', $volontaire->montant_paye) }}" required>
                </div>

                <div class="field">
                    <label>Date du paiement *</label>
                    <input class="input" type="date" name="date_paiement" value="{{ old('date_paiement', $volontaire->date_paiement->toDateString()) }}" required>
                </div>
            </div>

            <div style="margin-top:24px; display:flex; gap:12px; align-items:center;">
                <button class="btn btn--primary" type="submit">Mettre à jour</button>
                <a href="{{ route('tresorerie.cotisations.index', ['tab' => 'volontaire']) }}" style="color:var(--muted); font-size:14px; font-weight:600;">Annuler</a>
            </div>
        </form>
    </div>
</x-member-layout>
