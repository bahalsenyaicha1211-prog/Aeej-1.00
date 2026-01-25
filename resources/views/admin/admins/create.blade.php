@extends('layouts.admin')

@section('title', 'Admin • Ajouter un admin')
@section('header', 'Ajouter un admin')

@section('content')
<div class="page__head">
    <div>
        <h1 class="page__title">Ajouter un admin</h1>
        <p class="page__subtitle">Créer un nouveau compte administrateur.</p>
    </div>
    <div class="page__actions">
        <a class="btn" href="{{ route('admin.admins.index') }}">← Retour</a>
    </div>
</div>

<div class="card">
    <div class="card__body">
        <form class="form" method="POST" action="{{ route('admin.admins.store') }}">
            @csrf

            <div class="grid grid--2">
                <div class="field">
                    <label class="label">Nom <span class="req">*</span></label>
                    <input class="input" name="name" value="{{ old('name') }}" required>
                    @error('name') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label class="label">Email <span class="req">*</span></label>
                    <input class="input" type="email" name="email" value="{{ old('email') }}" required>
                    @error('email') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label class="label">Mot de passe <span class="req">*</span></label>
                    <input class="input" type="password" name="password" required>
                    @error('password') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="field">
                    <label class="label">Confirmer mot de passe <span class="req">*</span></label>
                    <input class="input" type="password" name="password_confirmation" required>
                </div>
            </div>

            <div class="card__foot">
                <button class="btn btn--primary" type="submit">Créer</button>
                <a class="btn btn--ghost" href="{{ route('admin.admins.index') }}">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
