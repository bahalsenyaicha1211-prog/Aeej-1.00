{{-- Champs partagés create / edit. $partenaire est null en création. --}}
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:22px;">

    <div class="field">
        <label class="admKpi__label text-white">Nom du partenaire *</label>
        <input class="input" name="nom" value="{{ old('nom', $partenaire->nom ?? '') }}" required>
        @error('nom') <div style="color:#fb7185; font-size:12px; margin-top:5px;">⚠️ {{ $message }}</div> @enderror
    </div>

    <div class="field">
        <label class="admKpi__label text-white">Catégorie</label>
        <select class="input" name="partner_category_id">
            <option value="">— Non classé —</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected((string) old('partner_category_id', $partenaire->partner_category_id ?? '') === (string) $cat->id)>{{ $cat->nom }}</option>
            @endforeach
        </select>
        @error('partner_category_id') <div style="color:#fb7185; font-size:12px; margin-top:5px;">⚠️ {{ $message }}</div> @enderror
    </div>

    <div class="field" style="grid-column: 1 / -1;">
        <label class="admKpi__label text-white">Site web (facultatif)</label>
        <input class="input" type="url" name="url" value="{{ old('url', $partenaire->url ?? '') }}" placeholder="https://…">
        @error('url') <div style="color:#fb7185; font-size:12px; margin-top:5px;">⚠️ {{ $message }}</div> @enderror
    </div>

    <div class="field" style="grid-column: 1 / -1;">
        <label class="admKpi__label text-white">Description *</label>
        <textarea class="input" name="description" rows="5" required>{{ old('description', $partenaire->description ?? '') }}</textarea>
        @error('description') <div style="color:#fb7185; font-size:12px; margin-top:5px;">⚠️ {{ $message }}</div> @enderror
    </div>

    <div class="field" style="grid-column: 1 / -1;">
        <label class="admKpi__label text-white">Logo {{ $partenaire ? '' : '*' }}</label>
        <x-image-upload name="logo" folder="partenaires"
                        :current="$partenaire?->logo_url"
                        :required="! $partenaire"
                        label="Logo du partenaire"
                        hint="PNG à fond transparent de préférence, JPG ou WEBP — 4 Mo max." />
        @error('logo') <div style="color:#fb7185; font-size:12px; margin-top:5px;">⚠️ {{ $message }}</div> @enderror
        @error('logo_url') <div style="color:#fb7185; font-size:12px; margin-top:5px;">⚠️ {{ $message }}</div> @enderror
    </div>

    <div class="admRow" style="grid-column: 1 / -1; justify-content: flex-start; gap: 15px; background: rgba(255,255,255,0.02);">
        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $partenaire->is_published ?? true) ? 'checked' : '' }} style="width:20px; height:20px; accent-color:#22c55e;">
        <label>Afficher sur la page publique</label>
    </div>
</div>
