@extends('layouts.admin')

@section('title', 'Admin • Modifier bureau')
@section('header', 'Modifier membre du bureau')

@section('content')
<div class="card">
    <div class="toolbar">
        <div>
            <div style="font-weight:800; font-size:18px;">Modifier</div>
            <div class="help">Met à jour le poste, l’ordre, l’activation et la photo.</div>
        </div>
        <a class="btn btn--ghost" href="{{ route('admin.bureau.index') }}">← Retour</a>
    </div>

    <form class="form" method="POST" action="{{ route('admin.bureau.update', $bureau) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="field">
                <label>Membre (matricule)</label>
                <select class="select" name="matricule" required>
                    @foreach($membres as $m)
                        <option value="{{ $m->matricule }}" {{ old('matricule', $bureau->matricule) == $m->matricule ? 'selected' : '' }}>
                            {{ $m->prenom }} {{ $m->nom }} • {{ $m->matricule }}
                        </option>
                    @endforeach
                </select>
                @error('matricule') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>Poste</label>
                <input class="input" name="poste" value="{{ old('poste', $bureau->poste) }}" required>
                @error('poste') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="row">
            <div class="field">
                <label>Ordre</label>
                <input class="input" type="number" name="ordre" min="0" max="9999" value="{{ old('ordre', $bureau->ordre) }}">
                @error('ordre') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>

            <div class="field">
                <label>Photo</label>

                @if($bureau->photo)
                    <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap; margin-bottom:10px;">
                        <img src="{{ asset('storage/'.$bureau->photo) }}"
                             style="width:120px;height:80px;object-fit:cover;border-radius:14px;border:1px solid rgba(255,255,255,0.10);"
                             alt="Photo bureau">
                        <label style="display:flex; gap:10px; align-items:center;">
                            <input type="checkbox" name="remove_photo" value="1">
                            <span>Retirer la photo actuelle</span>
                        </label>
                    </div>
                @endif

                <input class="input" type="file" name="photo" accept="image/*">
                @error('photo') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="field">
            <label>Activer</label>
            <label style="display:flex; gap:10px; align-items:center;">
                <input type="checkbox" name="is_actif" value="1" {{ old('is_actif', $bureau->is_actif) ? 'checked' : '' }}>
                <span>Afficher sur le site public</span>
            </label>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button class="btn btn--primary" type="submit">Mettre à jour</button>
            <a class="btn" href="{{ route('admin.bureau.index') }}">Annuler</a>
        </div>
    </form>
</div>
@endsection
