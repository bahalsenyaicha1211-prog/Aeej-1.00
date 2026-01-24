{{-- resources/views/profile/partials/update-password-form.blade.php --}}
<section class="section">
    <header class="section__head">
        <h2 class="section__title">Mot de passe</h2>
        <p class="section__desc">Utilise un mot de passe long et unique.</p>
    </header>

    @if ($errors->updatePassword->any())
        <div class="alert alert--danger">
            <ul class="list">
                @foreach($errors->updatePassword->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="form">
        @csrf
        @method('PUT')

        <div class="field">
            <label class="label" for="current_password">Mot de passe actuel</label>
            <input class="input" id="current_password" name="current_password" type="password" autocomplete="current-password">
        </div>

        <div class="field">
            <label class="label" for="password">Nouveau mot de passe</label>
            <input class="input" id="password" name="password" type="password" autocomplete="new-password">
        </div>

        <div class="field">
            <label class="label" for="password_confirmation">Confirmer</label>
            <input class="input" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
        </div>

        <div class="actions">
            <button class="btn btn--dark" type="submit">Mettre à jour</button>
        </div>
    </form>
</section>
