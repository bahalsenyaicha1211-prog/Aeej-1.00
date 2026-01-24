{{-- resources/views/profile/edit.blade.php --}}
<x-member-layout :unreadAnnoncesCount="$unreadAnnoncesCount ?? 0">
    <x-slot name="header">
        Mon profil
    </x-slot>

    <div class="page">
        <div class="page__head">
            <h1 class="page__title">Mon profil</h1>
            <p class="page__subtitle">Modifie tes infos, ta photo et ta sécurité.</p>
        </div>

        <div class="grid">
            {{-- Photo --}}
            <div class="card">
                @include('profile.partials.update-profile-photo-form')
            </div>

            {{-- Infos --}}
            <div class="card">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Mot de passe --}}
            <div class="card">
                @include('profile.partials.update-password-form')
            </div>

            {{-- Suppression --}}
            <div class="card card--danger">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-member-layout>
