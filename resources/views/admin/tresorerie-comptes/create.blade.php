@extends('layouts.admin')

@section('title', 'Admin • Nouveau compte trésorerie')
@section('header', 'Trésorerie')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Attribuer un rôle trésorerie</h1>
            <p class="admDash__sub">Sélectionnez un membre déjà inscrit sur la plateforme — aucun nouveau compte n'est créé.</p>
        </div>
        <a class="admQuick__btn" href="{{ route('admin.tresorerie-comptes.index') }}" style="text-decoration: none;">← Retour</a>
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
        <div class="admPanel" style="grid-column: span 8;">
            <div class="admPanel__body">
                <form class="admRows" method="POST" action="{{ route('admin.tresorerie-comptes.store') }}" id="role-form">
                    @csrf

                    <div class="field">
                        <label class="admKpi__label text-white" style="display:block; margin-bottom: 10px;">Rôle *</label>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <label style="display:flex; align-items:center; gap:10px; color:#e2e8f0; font-size:13px; cursor:pointer;">
                                <input type="radio" name="role_tresorier" value="tresorier" class="role-radio" data-groupe="bureau" {{ old('role_tresorier') === 'tresorier' ? 'checked' : '' }} required>
                                Trésorier — peut enregistrer des paiements de cotisation
                            </label>
                            <label style="display:flex; align-items:center; gap:10px; color:#e2e8f0; font-size:13px; cursor:pointer;">
                                <input type="radio" name="role_tresorier" value="chef_tresorier" class="role-radio" data-groupe="bureau" {{ old('role_tresorier') === 'chef_tresorier' ? 'checked' : '' }}>
                                Chef trésorier — voit tous les paiements et la caisse (un seul à la fois, remplace l'ancien chef)
                            </label>
                            <label style="display:flex; align-items:center; gap:10px; color:#e2e8f0; font-size:13px; cursor:pointer;">
                                <input type="radio" name="role_tresorier" value="commissaire" class="role-radio" data-groupe="membres" {{ old('role_tresorier') === 'commissaire' ? 'checked' : '' }}>
                                Commissaire aux comptes — enregistre les dépenses et voit la caisse
                            </label>
                        </div>
                    </div>

                    <div class="field groupe-select" data-groupe="bureau" style="margin-top: 20px;">
                        <label class="admKpi__label text-white">Membre du bureau (poste Trésorier / Trésorière) *</label>
                        <select class="input" name="matricule" style="width:100%;">
                            <option value="">— Sélectionner —</option>
                            @forelse($tresoriersDisponibles as $b)
                                <option value="{{ $b->matricule }}" {{ old('matricule') === $b->matricule ? 'selected' : '' }}>
                                    {{ $b->membre->prenom }} {{ $b->membre->nom }} — {{ $b->poste }}
                                </option>
                            @empty
                            @endforelse
                        </select>
                        @if($tresoriersDisponibles->isEmpty())
                            <p style="color:#fbbf24; font-size:12px; margin-top:8px;">
                                Aucun membre du bureau disponible avec le poste Trésorier/Trésorière. Ajoutez-en un depuis <strong>Bureau</strong> d'abord.
                            </p>
                        @endif
                    </div>

                    <div class="field groupe-select" data-groupe="membres" style="margin-top: 20px; display:none;">
                        <label class="admKpi__label text-white">Membre *</label>
                        <select class="input" name="matricule_membre" style="width:100%;">
                            <option value="">— Sélectionner —</option>
                            @foreach($membresDisponibles as $m)
                                <option value="{{ $m->matricule }}" {{ old('matricule') === $m->matricule ? 'selected' : '' }}>
                                    {{ $m->prenom }} {{ $m->nom }} — {{ $m->matricule }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-top: 30px; display: flex; gap: 12px; align-items: center;">
                        <button class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 12px 30px; font-weight: 800; border: none; cursor: pointer;" type="submit">
                            Attribuer le rôle
                        </button>
                        <a href="{{ route('admin.tresorerie-comptes.index') }}" style="color: #64748b; font-size: 14px; text-decoration: none; font-weight: 600;">Annuler</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="admPanel" style="grid-column: span 4; background: rgba(59, 130, 246, 0.05); border-color: rgba(59, 130, 246, 0.2);">
            <div class="admPanel__body">
                <h3 style="color: #60a5fa; font-size: 14px; font-weight: 800; margin-bottom: 10px;">💰 Trésorerie</h3>
                <p style="color: #94a3b8; font-size: 12px; line-height: 1.6;">
                    La personne garde son compte membre habituel (mêmes identifiants). Elle accède simplement en plus à l'espace trésorerie.
                    Désigner un nouveau chef trésorier retire automatiquement ce statut à l'ancien.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
(() => {
    const radios = document.querySelectorAll('.role-radio');
    const groupes = document.querySelectorAll('.groupe-select');
    const selectBureau = document.querySelector('select[name="matricule"]');
    const selectMembre = document.querySelector('select[name="matricule_membre"]');

    function maj() {
        const coche = document.querySelector('.role-radio:checked');
        const groupeActif = coche ? coche.dataset.groupe : null;

        groupes.forEach(g => {
            const actif = g.dataset.groupe === groupeActif;
            g.style.display = actif ? 'block' : 'none';
        });

        // Un seul select doit porter name="matricule" à la fois pour la soumission
        if (groupeActif === 'membres') {
            selectBureau.removeAttribute('name');
            selectMembre.setAttribute('name', 'matricule');
        } else {
            selectMembre.removeAttribute('name');
            selectBureau.setAttribute('name', 'matricule');
        }
    }

    radios.forEach(r => r.addEventListener('change', maj));
    maj();
})();
</script>
@endsection
