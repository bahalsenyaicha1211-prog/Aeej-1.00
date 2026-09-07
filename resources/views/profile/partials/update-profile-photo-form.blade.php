@php($user = auth()->user())

<section class="section">
    <header class="section__head">
        <h3 class="section__title">Photo de profil</h3>
        <p class="section__desc">Facultatif. Affichée en rond dans votre espace. Sans photo, ce sont vos initiales qui s'affichent.</p>
    </header>

    @if ($errors->updatePhoto->any())
        <div class="alert alert--danger">
            <ul class="list">
                @foreach ($errors->updatePhoto->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="display:flex; align-items:center; gap:18px; flex-wrap:wrap;">
        <x-avatar :user="$user" :size="72" />

        <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="form" style="flex:1; min-width:240px;">
            @csrf
            @method('PATCH')

            <div class="field">
                <label class="label" for="photo">Choisir une image</label>
                <input class="input" id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp">
                <span class="field__hint">JPG, PNG ou WEBP — 2 Mo maximum.</span>
            </div>

            <div class="actions" style="display:flex; gap:10px; flex-wrap:wrap;">
                <button class="btn btn--dark" type="submit">Enregistrer la photo</button>

                @if ($user->profile_photo_path)
                    <button class="btn" type="submit" name="remove_photo" value="1"
                            style="background:#fff; border:1px solid var(--pf-border); color:var(--pf-muted);"
                            onclick="return confirm('Revenir aux initiales ?');">
                        Supprimer la photo
                    </button>
                @endif
            </div>
        </form>
    </div>
</section>
