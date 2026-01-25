@extends('layouts.admin')





@section('title', 'Admin • Modifier pays')
@section('header', 'Modifier un pays')

@section('content')
<div class="card">
    <div class="toolbar">
        <div>
            <div style="font-weight:800; font-size:18px;">Modifier</div>
            <div class="help">Met à jour le nom du pays.</div>
        </div>
        <a class="btn btn--ghost" href="{{ route('admin.pays.index') }}">← Retour</a>
    </div>

    <form class="form" method="POST" action="{{ route('admin.pays.update', $pays) }}">
        @csrf
        @method('PUT')

        <div class="field">
            <label>Nom</label>
            <input class="input" name="nom" value="{{ old('nom', $pays->nom) }}" required>
            @error('nom') <div class="help" style="color:#fb7185;">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button class="btn btn--primary" type="submit">Mettre à jour</button>
            <a class="btn" href="{{ route('admin.pays.index') }}">Annuler</a>
        </div>
    </form>
</div>
@endsection
