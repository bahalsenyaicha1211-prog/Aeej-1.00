{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}
<section class="section">
    <header class="section__head">
        <h2 class="section__title">Informations du profil</h2>
        <p class="section__desc">Modifie ton nom et ton email.</p>
    </header>

    @if ($errors->any())
        <div class="alert alert--danger">
            <ul class="list">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" class="form" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="field">
            <label class="label" for="name">Nom</label>
            <input class="input" id="name" name="name" type="text"
                   value="{{ old('name', $user->name) }}" required autocomplete="name">
        </div>

        <div class="field">
            <label class="label" for="email">Email</label>
            <input class="input" id="email" name="email" type="email"
                   value="{{ old('email', $user->email) }}" required autocomplete="username">

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="note">
                    Ton email n’est pas vérifié.
                    <button form="send-verification" class="linkBtn" type="submit">
                        Renvoyer l’email de vérification
                    </button>
                </div>
            @endif
        </div>

        <div class="actions">
            <button class="btn btn--dark" type="submit">Enregistrer</button>
        </div>
    </form>

    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>
</section>
