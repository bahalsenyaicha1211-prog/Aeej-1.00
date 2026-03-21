@extends('layouts.admin')

@section('title', 'Admin • Modifier membre')
@section('header', 'Édition Membre')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Modifier le profil</h1>
            <p class="admDash__sub">Mise à jour des informations de <span style="color:#4ade80;">{{ $membre->prenom }} {{ $membre->nom }}</span></p>
        </div>
        <a class="admQuick__btn" href="{{ route('admin.membres.show', $membre) }}" style="text-decoration:none;">← Annuler</a>
    </div>

    <div class="admPanel admPanel--full">
        <div class="admPanel__body">
            <form action="{{ route('admin.membres.update', $membre) }}" method="POST">
                @csrf @method('PUT')
                
                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:20px;">
                    <div class="field">
                        <label class="admKpi__label text-white">Nom</label>
                        <input class="input" name="nom" value="{{ old('nom', $membre->nom) }}" required>
                    </div>
                    <div class="field">
                        <label class="admKpi__label text-white">Prénom</label>
                        <input class="input" name="prenom" value="{{ old('prenom', $membre->prenom) }}" required>
                    </div>
                    <div class="field">
                        <label class="admKpi__label text-white">Sexe</label>
                        <select class="input" name="sexe" style="background:#0f172a; color:#fff;">
                            <option value="M" {{ old('sexe', $membre->sexe) == 'M' ? 'selected' : '' }}>Masculin (M)</option>
                            <option value="F" {{ old('sexe', $membre->sexe) == 'F' ? 'selected' : '' }}>Féminin (F)</option>
                        </select>
                    </div>
                    <div class="field">
                        <label class="admKpi__label text-white">Année d'adhésion</label>
                        <input class="input" type="number" name="annee_adhesion" value="{{ old('annee_adhesion', $membre->annee_adhesion) }}">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-top:20px;">
                    <div class="field">
                        <label class="admKpi__label text-white">Département</label>
                        <select class="input" name="iddep" style="background:#0f172a; color:#fff;">
                            @foreach($departements as $d)
                                <option value="{{ $d->iddep }}" {{ old('iddep', $membre->iddep) == $d->iddep ? 'selected' : '' }}>{{ $d->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label class="admKpi__label text-white">Pays</label>
                        <select class="input" name="idpays" style="background:#0f172a; color:#fff;">
                            @foreach($pays as $p)
                                <option value="{{ $p->idpays }}" {{ old('idpays', $membre->idpays) == $p->idpays ? 'selected' : '' }}>{{ $p->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-top:20px;">
                    <div class="field">
                        <label class="admKpi__label text-white">Téléphone</label>
                        <input class="input" name="telephone" value="{{ old('telephone', $membre->telephone) }}">
                    </div>
                    <div class="field">
                        <label class="admKpi__label text-white">Email professionnel</label>
                        <input class="input" type="email" name="email" value="{{ old('email', $membre->email) }}" required>
                    </div>
                </div>

                <div class="field" style="margin-top:20px;">
                    <label class="admKpi__label text-white">Adresse postale</label>
                    <textarea class="input" name="adresse" rows="3">{{ old('adresse', $membre->adresse) }}</textarea>
                </div>

                <div style="margin-top:30px; display:flex; justify-content:flex-end; gap:10px;">
                    <button class="btn" style="background:#22c55e; color:#fff; border-radius:12px; padding:12px 30px; font-weight:800; border:none; cursor:pointer;" type="submit">Sauvegarder les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection