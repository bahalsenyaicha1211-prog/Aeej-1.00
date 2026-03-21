@extends('layouts.admin')

@section('title', 'Admin • Nouvelle activité')
@section('header', 'Créer une activité')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Nouvelle Activité</h1>
            <p class="admDash__sub">Enregistrez un nouvel événement dans le journal de l'association.</p>
        </div>
        <a class="admQuick__btn" href="{{ route('admin.activites.index') }}" style="text-decoration: none;">← Retour</a>
    </div>

    <div class="admGrid">
        <div class="admPanel" style="grid-column: span 8;">
            <div class="admPanel__body">
                <form class="admRows" method="POST" action="{{ route('admin.activites.store') }}">
                    @csrf
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="field" style="grid-column: span 2;">
                            <label class="admKpi__label text-white">Libellé de l'activité *</label>
                            <input class="input" name="libelle" value="{{ old('libelle') }}" required placeholder="ex: Assemblée Générale Annuelle" style="width: 100%;">
                            @error('libelle') <div style="color:#fb7185; font-size: 12px; margin-top: 5px;">⚠️ {{ $message }}</div> @enderror
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Catégorie</label>
                            <input class="input" name="categorie" value="{{ old('categorie') }}" placeholder="ex: Social, Sport, Réunion...">
                            @error('categorie') <div style="color:#fb7185; font-size: 12px; margin-top: 5px;">⚠️ {{ $message }}</div> @enderror
                        </div>

                        <div class="field">
                            <label class="admKpi__label text-white">Date de réalisation *</label>
                            <input class="input" type="date" name="date" value="{{ old('date') }}" required style="background: rgba(0,0,0,0.2); color: #fff;">
                            @error('date') <div style="color:#fb7185; font-size: 12px; margin-top: 5px;">⚠️ {{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div style="margin-top: 30px; display: flex; gap: 15px; align-items: center;">
                        <button class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 12px 30px; font-weight: 800; border: none; cursor: pointer;" type="submit">
                            Enregistrer l'activité
                        </button>
                        <a href="{{ route('admin.activites.index') }}" style="color: #64748b; font-size: 14px; text-decoration: none; font-weight: 600;">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection