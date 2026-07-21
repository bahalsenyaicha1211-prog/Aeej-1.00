<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" minlength="8" />
            <p style="font-size:12px; color:var(--text-dim); margin-top:6px;">Minimum 8 caractères.</p>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            <ul id="password-match-msg" class="field-error" style="display:none;"><li>Les mots de passe ne correspondent pas.</li></ul>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button id="reset-submit-btn">
                {{ __('Réinitialiser le mot de passe') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        (() => {
            const password = document.getElementById('password');
            const confirmation = document.getElementById('password_confirmation');
            const message = document.getElementById('password-match-msg');
            const submitBtn = document.getElementById('reset-submit-btn');
            if (!password || !confirmation) return;

            const check = () => {
                if (confirmation.value.length === 0) {
                    message.style.display = 'none';
                    confirmation.classList.remove('field-invalid');
                    return true;
                }
                const matches = password.value === confirmation.value;
                message.style.display = matches ? 'none' : 'block';
                confirmation.classList.toggle('field-invalid', !matches);
                return matches;
            };

            password.addEventListener('input', check);
            confirmation.addEventListener('input', check);

            document.querySelector('form').addEventListener('submit', (e) => {
                if (!check()) {
                    e.preventDefault();
                    confirmation.focus();
                }
            });
        })();
    </script>
</x-guest-layout>
