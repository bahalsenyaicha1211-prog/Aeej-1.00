{{-- resources/views/profile/partials/update-profile-photo-form.blade.php --}}
<section class="section">
    <header class="section__head">
        <h2 class="section__title">Photo de profil</h2>
        <p class="section__desc">Ajoute une photo pour personnaliser ton compte.</p>
    </header>

    @if (session('success'))
        <div class="alert alert--success">{{ session('success') }}</div>
    @endif

    @if ($errors->has('photo'))
        <div class="alert alert--danger">{{ $errors->first('photo') }}</div>
    @endif

    <form method="POST"
          action="{{ route('profile.photo.update') }}"
          enctype="multipart/form-data"
          class="form">
        @csrf
        @method('PATCH')

        @php
            $u = auth()->user();
            $avatar = $u->profile_photo_path
                ? asset('storage/'.$u->profile_photo_path)
                : asset('images/default-avatar.png');
        @endphp

        <div class="avatarRow">
            <img id="avatarPreview"
                 class="avatar"
                 src="{{ $avatar }}"
                 alt="Photo de profil">

            <div class="avatarRow__actions">
                <label class="btn btn--primary">
                    Choisir une photo
                    <input id="photoInput" type="file" name="photo" accept="image/*" class="srOnly">
                </label>

                <div class="hint">JPG, PNG, WEBP — max 2 Mo</div>

                <div class="btnRow">
                    <button type="submit" class="btn btn--dark">Enregistrer</button>

                    @if($u->profile_photo_path)
                        <button type="submit" name="remove_photo" value="1" class="btn btn--ghost">
                            Retirer la photo
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </form>
</section>
