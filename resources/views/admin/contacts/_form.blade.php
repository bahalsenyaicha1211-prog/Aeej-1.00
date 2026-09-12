{{-- Champs partagés create / edit. $personne est null en création. --}}
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:22px;">

    <div class="field">
        <label class="admKpi__label text-white">Nom complet *</label>
        <input class="input" name="nom" value="{{ old('nom', $personne->nom ?? '') }}" placeholder="Ex. MOHADED DIANE" required>
        @error('nom') <div style="color:#fb7185; font-size:12px; margin-top:5px;">⚠️ {{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label class="admKpi__label text-white">Fonction / poste *</label>
        <input class="input" name="poste" value="{{ old('poste', $personne->poste ?? '') }}" placeholder="Ex. Président" required>
        @error('poste') <div style="color:#fb7185; font-size:12px; margin-top:5px;">⚠️ {{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label class="admKpi__label text-white">Téléphone</label>
        <input class="input" name="telephone" value="{{ old('telephone', $personne->telephone ?? '') }}" placeholder="+216 …">
        @error('telephone') <div style="color:#fb7185; font-size:12px; margin-top:5px;">⚠️ {{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label class="admKpi__label text-white">Email</label>
        <input class="input" type="email" name="email" value="{{ old('email', $personne->email ?? '') }}" placeholder="nom@exemple.com">
        @error('email') <div style="color:#fb7185; font-size:12px; margin-top:5px;">⚠️ {{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label class="admKpi__label text-white">Ordre d'affichage</label>
        <input class="input" type="number" min="0" name="position" value="{{ old('position', $personne->position ?? 0) }}">
        <span class="field__hint" style="color:#64748b;">Les cartes s'affichent par ordre croissant.</span>
    </div>

    <div class="field" style="grid-column: 1 / -1;">
        <label class="admKpi__label text-white">Photo {{ $personne ? '' : '*' }}</label>
        <x-image-upload name="photo" folder="contacts"
                        :current="$personne?->photo_url"
                        :required="! $personne"
                        label="Photo de la personne"
                        hint="Portrait, format vertical de préférence — JPG, PNG ou WEBP, 4 Mo max." />
        @error('photo') <div style="color:#fb7185; font-size:12px; margin-top:5px;">⚠️ {{ $message }}</div> @enderror
        @error('photo_url') <div style="color:#fb7185; font-size:12px; margin-top:5px;">⚠️ {{ $message }}</div> @enderror
    </div>

    <div class="admRow" style="grid-column: 1 / -1; justify-content: flex-start; gap: 15px; background: rgba(255,255,255,0.02);">
        <input type="checkbox" name="is_highlighted" value="1" {{ old('is_highlighted', $personne->is_highlighted ?? false) ? 'checked' : '' }} style="width:20px; height:20px; accent-color:#f59e0b;">
        <label>Mettre en avant (bordure dorée, ex. président)</label>
    </div>

    <div class="admRow" style="grid-column: 1 / -1; justify-content: flex-start; gap: 15px; background: rgba(255,255,255,0.02);">
        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $personne->is_published ?? true) ? 'checked' : '' }} style="width:20px; height:20px; accent-color:#22c55e;">
        <label>Afficher sur la page publique « Contact »</label>
    </div>
</div>
