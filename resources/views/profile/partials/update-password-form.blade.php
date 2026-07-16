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
            <small id="password-match-msg" style="display:none; color:var(--profile-danger,#e53e3e); font-weight:600;">Les mots de passe ne correspondent pas.</small>
        </div>

        <div class="actions">
            <button class="btn btn--dark" type="submit" id="password-update-submit">Mettre à jour</button>
        </div>
    </form>
</section>

<script>
    (() => {
        const password = document.getElementById('password');
        const confirmation = document.getElementById('password_confirmation');
        const message = document.getElementById('password-match-msg');
        if (!password || !confirmation) return;

        const check = () => {
            if (confirmation.value.length === 0) {
                message.style.display = 'none';
                confirmation.style.borderColor = '';
                return true;
            }
            const matches = password.value === confirmation.value;
            message.style.display = matches ? 'none' : 'block';
            confirmation.style.borderColor = matches ? '' : 'var(--profile-danger,#e53e3e)';
            return matches;
        };

        password.addEventListener('input', check);
        confirmation.addEventListener('input', check);

        confirmation.closest('form').addEventListener('submit', (e) => {
            if (!check()) {
                e.preventDefault();
                confirmation.focus();
            }
        });
    })();
</script>
