@extends('layouts.admin')

@section('title', 'Admin • Modifier admin')
@section('header', 'Modifier admin')

@section('content')
<div class="page__head">
    <div>
        <h1 class="page__title">Modifier admin</h1>
        <p class="page__subtitle">Mettre à jour le compte administrateur.</p>
    </div>
    <div class="page__actions">
        <a class="btn" href="{{ route('admin.admins.index') }}">← Retour</a>
    </div>
</div>

<div class="card">
    <div class="card__body">
        <form class="form" method="POST" action="{{ route('admin.admins.update', $admin) }}">
            @csrf
            @method('PUT')

            <div class="grid grid--2">
                <div class="field">
                    <label class="label">Nom <span class="req">*</span></label>
                    <input class="input" name="name" value="{{ old('name', $admin->name) }}" required>
                    @error('name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label class="label">Email <span class="req">*</span></label>
                    <input class="input" type="email" name="email" value="{{ old('email', $admin->email) }}" required>
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field field--full">
                    <label class="label">Nouveau mot de passe (optionnel)</label>
                    <input class="input" type="password" name="password" placeholder="Laisse vide pour ne pas changer">
                    @error('password') <div class="error">{{ $message }}</div> @enderror
                    <div class="hint">Min 8 caractères.</div>
                </div>

                <div class="field field--full">
                    <label class="label">Confirmer nouveau mot de passe</label>
                    <input class="input" type="password" name="password_confirmation">
                </div>
            </div>

            <div class="card__foot">
                <button class="btn btn--primary" type="submit">Enregistrer</button>
                <a class="btn btn--ghost" href="{{ route('admin.admins.index') }}">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
