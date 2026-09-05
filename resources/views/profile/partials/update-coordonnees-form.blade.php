{{-- resources/views/profile/partials/update-coordonnees-form.blade.php --}}
@php($membre = auth()->user()->membre)

<section class="section">
    <header class="section__head">
        <h3 class="section__title">Téléphone &amp; adresse</h3>
        <p class="section__desc">Ces coordonnées restent visibles uniquement par le bureau.</p>
    </header>

    @if ($errors->updateCoordonnees->any())
        <div class="alert alert--danger">
            <ul class="list">
                @foreach($errors->updateCoordonnees->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (! $membre)
        <p class="field__hint">Aucune fiche membre n'est associée à ce compte.</p>
    @else
        <form method="POST" action="{{ route('profile.coordonnees.update') }}" class="form">
            @csrf
            @method('PATCH')

            <div class="field">
                <label class="label" for="telephone">Téléphone</label>
                <input class="input" id="telephone" name="telephone" type="tel"
                       inputmode="tel" autocomplete="tel"
                       value="{{ old('telephone', $membre->telephone) }}"
                       placeholder="Ex. +216 20 123 456">
                <span class="field__hint">Chiffres, espaces et symboles + ( ) - autorisés.</span>
            </div>

            <div class="field">
                <label class="label" for="adresse">Adresse</label>
                <input class="input" id="adresse" name="adresse" type="text"
                       autocomplete="street-address"
                       value="{{ old('adresse', $membre->adresse) }}"
                       placeholder="Ex. Cité El Bassatine, Jendouba">
            </div>

            <div class="actions">
                <button class="btn btn--dark" type="submit">Enregistrer</button>
            </div>
        </form>
    @endif
</section>
