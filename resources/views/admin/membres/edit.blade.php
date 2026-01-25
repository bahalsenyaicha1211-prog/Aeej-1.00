@extends('layouts.admin')

@section('title', 'Admin • Modifier membre')
@section('header', 'Modifier un membre')

@section('content')
<div class="card">
    <div class="toolbar">
        <div>
            <div style="font-weight:800; font-size:18px;">
                Modifier : {{ $membre->prenom }} {{ $membre->nom }}
            </div>
            <div class="help">Matricule : {{ $membre->matricule }}</div>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a class="btn btn--ghost" href="{{ route('admin.membres.show', $membre) }}">← Retour</a>
        </div>
    </div>

    {{-- ✅ FORM UPDATE (seul) --}}
    <form class="form" method="POST" action="{{ route('admin.membres.update', $membre) }}">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="field">
                <label>Nom</label>
                <input class="input" name="nom" value="{{ old('nom', $membre->nom) }}" required>
                @error('nom') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>Prénom</label>
                <input class="input" name="prenom" value="{{ old('prenom', $membre->prenom) }}" required>
                @error('prenom') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="field">
                <label>Sexe</label>
                <select class="input" name="sexe" required>
                    <option value="M" {{ old('sexe', $membre->sexe) === 'M' ? 'selected' : '' }}>M</option>
                    <option value="F" {{ old('sexe', $membre->sexe) === 'F' ? 'selected' : '' }}>F</option>
                </select>
                @error('sexe') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>Année d’adhésion</label>
                <input class="input" type="number" name="annee_adhesion"
                       value="{{ old('annee_adhesion', $membre->annee_adhesion) }}"
                       min="2020" max="{{ date('Y') + 1 }}" required>
                @error('annee_adhesion') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="field">
                <label>Département</label>
                <select class="input" name="iddep" required>
                    @foreach($departements as $d)
                        <option value="{{ $d->iddep }}"
                            {{ (string)old('iddep', $membre->iddep) === (string)$d->iddep ? 'selected' : '' }}>
                            {{ $d->nom }}
                        </option>
                    @endforeach
                </select>
                @error('iddep') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>Pays</label>
                <select class="input" name="idpays" required>
                    @foreach($pays as $p)
                        <option value="{{ $p->idpays }}"
                            {{ (string)old('idpays', $membre->idpays) === (string)$p->idpays ? 'selected' : '' }}>
                            {{ $p->nom }}
                        </option>
                    @endforeach
                </select>
                @error('idpays') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="field">
                <label>Téléphone (optionnel)</label>
                <input class="input" name="telephone" value="{{ old('telephone', $membre->telephone) }}">
                @error('telephone') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>Mail</label>
                <input class="input" type="email" name="email" value="{{ old('email', $membre->email) }}" required>
                @error('email') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="field">
            <label>Adresse (optionnel)</label>
            <textarea class="input" name="adresse" rows="4">{{ old('adresse', $membre->adresse) }}</textarea>
            @error('adresse') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap; justify-content:flex-end;">
            <button class="btn btn--primary" type="submit">Mettre à jour</button>
            <a class="btn" href="{{ route('admin.membres.show', $membre) }}">Annuler</a>
        </div>
    </form>

    {{-- ✅ FORM DELETE séparé (pas imbriqué) --}}
    <div style="margin-top:14px; display:flex; justify-content:flex-end;">
        <form method="POST" action="{{ route('admin.membres.destroy', $membre) }}"
              onsubmit="return confirm('Supprimer définitivement ce membre ?');">
            @csrf
            @method('DELETE')
            <button class="btn btn--danger" type="submit">Supprimer</button>
        </form>
    </div>
</div>
@endsection
