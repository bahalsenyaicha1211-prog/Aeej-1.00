@php($user = auth()->user())

<div class="pf-avatar">
    <div class="pf-avatar__frame">
        <x-avatar :user="$user" :size="96" />

        {{-- Bouton caméra façon réseau social : ouvre le sélecteur de fichier
             et envoie directement le formulaire. --}}
        <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="pf-avatar__cam">
            @csrf
            @method('PATCH')
            <label title="Changer la photo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                    <circle cx="12" cy="13" r="4"/>
                </svg>
                <span class="pf-visually-hidden">Changer la photo de profil</span>
                <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" hidden
                       onchange="if(this.files.length) this.form.submit()">
            </label>
        </form>
    </div>

    @if ($errors->updatePhoto->any())
        <p class="pf-avatar__err">{{ $errors->updatePhoto->first() }}</p>
    @elseif ($user->profile_photo_path)
        <div class="pf-avatar__hint">
            <form method="POST" action="{{ route('profile.photo.update') }}" style="display:inline;">
                @csrf @method('PATCH')
                <button type="submit" name="remove_photo" value="1" class="pf-avatar__remove"
                        onclick="return confirm('Revenir aux initiales ?');">Retirer la photo</button>
            </form>
        </div>
    @else
        <p class="pf-avatar__hint">JPG, PNG ou WEBP — 2 Mo max.</p>
    @endif
</div>
