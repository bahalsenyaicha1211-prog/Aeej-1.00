@extends('layouts.admin')

@section('title', 'Admin • Ajouter bureau')
@section('header', 'Ajouter un membre du bureau')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Nouveau membre du bureau</h1>
            <p class="admDash__sub">Associez un membre existant à une fonction officielle du bureau public.</p>
        </div>
        {{-- Bouton Retour Stylisé --}}
        <a class="admQuick__btn" href="{{ route('admin.bureau.index') }}">← Retour à la liste</a>
    </div>

    <div class="admGrid">
        {{-- Panel Unique Full Width (Structure fluide) --}}
        <div class="admPanel admPanel--full">
            <div class="admPanel__body">
                <form class="admRows" method="POST" action="{{ route('admin.bureau.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Grille de champs (Modern Alignment) --}}
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        
                        {{-- Sélection du Membre (Matricule) --}}
                        <div class="field">
                            <label class="admKpi__label text-white">Choisir un membre inscrit</label>
                            {{-- Style Inline pour aligner le select sur le fond sombre --}}
                            <select class="input" name="matricule" required style="background: rgba(0,0,0,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.05);">
                                <option value="" style="background: #1f2937;">— Sélectionnez un membre —</option>
                                @foreach($membres as $m)
                                    <option value="{{ $m->matricule }}" {{ old('matricule') == $m->matricule ? 'selected' : '' }} style="background: #1f2937;">
                                        {{ $m->prenom }} {{ $m->nom }} • {{ $m->matricule }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- Affichage de l'erreur stylisé --}}
                            @error('matricule') <div class="help" style="color:#fb7185; font-size: 11px;">⚠️ {{ $message }}</div> @enderror
                        </div>

                        {{-- Poste/Fonction --}}
                        <div class="field">
                            <label class="admKpi__label text-white">Poste de fonction</label>
                            <input class="input" name="poste" value="{{ old('poste') }}" required placeholder="ex: Président(e) / Secrétaire">
                            @error('poste') <div class="help" style="color:#fb7185; font-size: 11px;">⚠️ {{ $message }}</div> @enderror
                        </div>

                        {{-- Ordre d'affichage --}}
                        <div class="field">
                            <label class="admKpi__label text-white">Ordre d'affichage (priorité)</label>
                            <input class="input" type="number" name="ordre" min="0" max="9999" value="{{ old('ordre', 0) }}">
                            @error('ordre') <div class="help" style="color:#fb7185; font-size: 11px;">⚠️ {{ $message }}</div> @enderror
                        </div>

                        {{-- Photo de bureau --}}
                        <div class="field">
                            <label class="admKpi__label text-white">Photo de bureau (optionnel)</label>
                            {{-- Petit ajustement pour que le bouton d'upload s'intègre bien --}}
                            <input class="input" type="file" name="photo" accept="image/*" style="font-size: 12px; padding: 10px;">
                            @error('photo') <div class="help" style="color:#fb7185; font-size: 11px;">⚠️ {{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Ligne de séparation subtile --}}
                    <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.05); margin: 20px 0;">

                    {{-- Toggle d'activation (Design moderne avec admRow) --}}
                    <div class="admRow" style="justify-content: flex-start; gap: 20px; border: 1px solid rgba(34,197,94,0.1); background: rgba(34,197,94,0.02);">
                        <input type="checkbox" name="is_actif" id="is_actif" value="1" {{ old('is_actif') ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: #22c55e; flex-shrink: 0;">
                        <label for="is_actif">
                            <div style="font-weight: 800; color: #fff;">Activer ce membre sur le site public</div>
                            <div style="font-size: 12px; color: #64748b;">Si coché, le membre sera visible dans la page "Bureau" de l'espace membre.</div>
                        </label>
                        @error('is_actif') <div class="help" style="color:#fb7185; font-size: 11px; margin-left: auto;">⚠️ {{ $message }}</div> @enderror
                    </div>

                    {{-- Zone de boutons d'action --}}
                    <div style="margin-top: 30px; display: flex; gap: 10px;">
                        <button class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 12px 30px; font-weight: 800;" type="submit">
                            Enregistrer le membre
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection