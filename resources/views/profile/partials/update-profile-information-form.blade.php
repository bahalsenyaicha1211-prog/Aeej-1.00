{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}
@php($user = auth()->user())

<section class="section">
    <header class="section__head">
        <h3 class="section__title">Nom affiché</h3>
        <p class="section__desc">Le nom utilisé dans votre espace membre et sur vos notifications.</p>
    </header>

    @if ($errors->updateProfile->any())
        <div class="alert alert--danger">
            <ul class="list">
                @foreach($errors->updateProfile->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" class="form">
        @csrf
        @method('PATCH')

        <div class="field">
            <label class="label" for="name">Nom affiché</label>
            <input class="input" id="name" name="name" type="text" required
                   autocomplete="name" value="{{ old('name', $user->name) }}">
        </div>

        <div class="field">
            <label class="label" for="email">Adresse e-mail</label>
            <input class="input" id="email" type="email" value="{{ $user->email }}" disabled>
            <span class="field__hint">Pour changer d'adresse e-mail, contactez le bureau.</span>
        </div>

        <div class="actions">
            <button class="btn btn--dark" type="submit">Enregistrer</button>
        </div>
    </form>
</section>
