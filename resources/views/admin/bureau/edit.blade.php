@extends('layouts.admin')

@section('title', 'Admin • Configurer membre')
@section('header', 'Configuration du Bureau')

@section('styles')
    <script src="{{ asset_v('js/image-upload.js') }}?v={{ filemtime(public_path('js/image-upload.js')) }}" defer></script>
@endsection

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Modifier le Membre</h1>
            <p class="admDash__sub">Ajustez le rôle et la visibilité au sein du bureau.</p>
        </div>
        <x-adm-back :href="route('admin.bureau.index')" />
    </div>

    <div class="admGrid">
        <div class="admPanel admPanel--full">
            <div class="admPanel__body">
                <form class="admRows" method="POST" action="{{ route('admin.bureau.update', $bureau) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        
                        {{-- Sélection Membre --}}
                        <div class="field">
                            <label class="admKpi__label text-white">Membre de l'organisation</label>
                            <select class="input" name="matricule" required style="background: rgba(0,0,0,0.2); color: #fff;">
                                @foreach($membres as $m)
                                    <option value="{{ $m->matricule }}" {{ old('matricule', $bureau->matricule) == $m->matricule ? 'selected' : '' }}>
                                        {{ $m->prenom }} {{ $m->nom }} • {{ $m->matricule }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Poste --}}
                        <div class="field">
                            <label class="admKpi__label text-white">Poste occupé</label>
                            <input class="input" name="poste" value="{{ old('poste', $bureau->poste) }}" required placeholder="ex: Secrétaire Général">
                        </div>

                        {{-- Ordre --}}
                        <div class="field">
                            <label class="admKpi__label text-white">Ordre d'affichage (priorité)</label>
                            <input class="input" type="number" name="ordre" value="{{ old('ordre', $bureau->ordre) }}">
                        </div>

                        {{-- Photo --}}
                        <div class="field" style="grid-column: 1 / -1;">
                            <label class="admKpi__label text-white">Photo de fonction (optionnel)</label>
                            <x-image-upload name="photo" folder="bureau" label="Photo de fonction"
                                            :current="$bureau->photo ? (str_starts_with($bureau->photo, 'http') ? $bureau->photo : asset('storage/'.$bureau->photo)) : null"
                                            hint="Portrait de préférence — JPG, PNG ou WEBP, 4 Mo max." />
                            @if($bureau->photo)
                                <label style="display:flex; align-items:center; gap:8px; margin-top:8px; font-size:12px; color:#94a3b8;">
                                    <input type="checkbox" name="remove_photo" value="1" style="width:16px;height:16px;accent-color:#ef4444;">
                                    Retirer la photo
                                </label>
                            @endif
                        </div>
                    </div>

                    <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.05); margin: 20px 0;">

                    {{-- Toggle Visibilité --}}
                    <div class="admRow" style="justify-content: flex-start; gap: 20px;">
                        <input type="checkbox" name="is_actif" id="is_actif" value="1" {{ old('is_actif', $bureau->is_actif) ? 'checked' : '' }} style="width: 20px; height: 20px; accent-color: #22c55e;">
                        <label for="is_actif">
                            <div style="font-weight: 800; color: #fff;">Activer ce membre sur le site</div>
                            <div style="font-size: 12px; color: #64748b;">Si coché, le membre sera visible dans la page "Bureau" du public.</div>
                        </label>
                    </div>

                    <div style="margin-top: 20px; display: flex; gap: 10px;">
                        <button class="btn" style="background: #3b82f6; color: #fff; border-radius: 12px; padding: 12px 30px; font-weight: 800;" type="submit">
                            Mettre à jour le profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection