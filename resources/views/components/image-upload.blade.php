@props([
    'name',
    'folder',
    'current' => null,
    'required' => false,
    'label' => 'Image',
    'hint' => 'PNG, JPG ou WEBP — 4 Mo max.',
    'multiple' => false,
])

@php $cfg = config('services.cloudinary'); @endphp

<div class="imgup" data-imgup
     data-cloud-name="{{ $cfg['cloud_name'] }}"
     data-upload-preset="{{ $cfg['upload_preset'] }}"
     data-folder="{{ $folder }}"
     data-name="{{ $name }}"
     @if($multiple) data-multiple @endif>

    {{-- Vide au départ : ne se remplit que lorsque le JS a envoyé une nouvelle
         image (ou après une erreur de validation via old()). En édition sans
         changement → le contrôleur conserve l'image existante. --}}
    @unless($multiple)
        <input type="hidden" name="{{ $name }}_url" value="{{ old($name.'_url') }}">
    @endunless

    <div class="imgup__zone" role="button" tabindex="0" aria-label="{{ $label }}">
        <img class="imgup__preview" src="{{ $current }}" alt="" @unless($current && !$multiple) hidden @endunless>

        <div class="imgup__empty" @if($current && !$multiple) hidden @endif>
            <x-icon name="upload" class="imgup__ic"/>
            <span class="imgup__cta">Cliquez ou glissez {{ $multiple ? 'des images' : 'une image' }} ici</span>
            @if($hint)<span class="imgup__hint">{{ $hint }}</span>@endif
        </div>

        <div class="imgup__bar" hidden><span></span></div>
    </div>

    <div class="imgup__status" hidden></div>

    {{-- Fallback sans JS + secours si Cloudinary échoue.
         Pas d'attribut `required` : un input caché requis bloque l'envoi de
         façon non focusable → la validation « image requise » se fait côté
         serveur (HandlesImageUpload::resolveImageUrl). --}}
    <input class="imgup__file" type="file"
           name="{{ $multiple ? 'images[]' : $name }}"
           accept="image/png,image/jpeg,image/webp"
           @if($multiple) multiple @endif
           hidden>

    <button type="button" class="imgup__change" @unless($current && !$multiple) hidden @endunless>Changer l'image</button>
</div>
