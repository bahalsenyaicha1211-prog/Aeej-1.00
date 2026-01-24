{{-- resources/views/profile/partials/delete-user-form.blade.php --}}
<section class="section">
    <header class="section__head">
        <h2 class="section__title">Supprimer le compte</h2>
        <p class="section__desc">Action irréversible. Saisis ton mot de passe pour confirmer.</p>
    </header>

    @if ($errors->userDeletion->any())
        <div class="alert alert--danger">
            <ul class="list">
                @foreach($errors->userDeletion->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.destroy') }}" class="form"
          onsubmit="return confirm('Confirmer la suppression définitive du compte ?');">
        @csrf
        @method('DELETE')

        <div class="field">
            <label class="label" for="delete_password">Mot de passe</label>
            <input class="input" id="delete_password" name="password" type="password" required>
        </div>

        <div class="actions">
            <button class="btn btn--danger" type="submit">Supprimer mon compte</button>
        </div>
    </form>
</section>
