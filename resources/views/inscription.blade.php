@extends('layouts.public')


@section('title', "Inscription - AEEJ")

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/inscription.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/inscription.js') }}" defer></script>
@endsection

@section('content')
<main class="auth-page">
    <section class="auth-card" aria-label="Formulaire d'inscription">
        <!-- Colonne visuelle -->
        <div class="auth-visual">
            <div class="brand">
                <div class="brand-logo">
                    <img src="{{ asset('images/drapeau/AEEJ.png') }}" alt="Logo AEEJ">
                </div>
                <div class="brand-text">
                    <h1>Inscription</h1>
                    <p>Rejoignez l’AEEJ et accédez à votre espace membre.</p>
                </div>
            </div>

            <ul class="brand-points">
                <li><i class="fa-solid fa-circle-check"></i> Informations sécurisées</li>
                <li><i class="fa-solid fa-circle-check"></i> Accès à vos cotisations</li>
                <li><i class="fa-solid fa-circle-check"></i> Activités et annonces</li>
            </ul>

            <div class="brand-foot">
                <span class="chip"><i class="fa-solid fa-shield-halved"></i> AEEJ Platform</span>
                <span class="chip"><i class="fa-solid fa-mobile-screen"></i> 100% Responsive</span>
            </div>
        </div>

        <!-- Colonne formulaire -->
        <div class="auth-form">
            <div class="form-head">
                <h2>Créer un compte membre</h2>
                <p>Renseignez vos informations. Les champs marqués * sont obligatoires.</p>
            </div>

            @if(session('success'))
                <div class="alert success">
                    <i class="fa-solid fa-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert error">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        <strong>Veuillez corriger les erreurs :</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form class="grid" action="{{ route('inscription.store') }}" method="POST" novalidate>
                @csrf

                <!-- Matricule -->
                <div class="field full">
                    <label for="matricule">Matricule <span>*</span></label>
                    <div class="input">
                        <i class="fa-solid fa-id-card"></i>
                        <input
                            id="matricule"
                            name="matricule"
                            type="text"
                            value="{{ old('matricule') }}"
                            placeholder="Ex: MEM010"
                            required
                            autocomplete="off"
                        >
                    </div>
                    @error('matricule') <small class="msg error">{{ $message }}</small> @enderror
                </div>

                <!-- Nom -->
                <div class="field">
                    <label for="nom">Nom <span>*</span></label>
                    <div class="input">
                        <i class="fa-solid fa-user"></i>
                        <input
                            id="nom"
                            name="nom"
                            type="text"
                            value="{{ old('nom') }}"
                            placeholder="Votre nom"
                            required
                        >
                    </div>
                    @error('nom') <small class="msg error">{{ $message }}</small> @enderror
                </div>

                <!-- Prénom -->
                <div class="field">
                    <label for="prenom">Prénom <span>*</span></label>
                    <div class="input">
                        <i class="fa-solid fa-user"></i>
                        <input
                            id="prenom"
                            name="prenom"
                            type="text"
                            value="{{ old('prenom') }}"
                            placeholder="Votre prénom"
                            required
                        >
                    </div>
                    @error('prenom') <small class="msg error">{{ $message }}</small> @enderror
                </div>

                <!-- Sexe -->
                <div class="field">
                    <label for="sexe">Sexe <span>*</span></label>
                    <div class="input">
                        <i class="fa-solid fa-venus-mars"></i>
                        <select id="sexe" name="sexe" required>
                            <option value="">Sélectionner</option>
                            <option value="M" {{ old('sexe') === 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('sexe') === 'F' ? 'selected' : '' }}>Féminin</option>
                        </select>
                    </div>
                    @error('sexe') <small class="msg error">{{ $message }}</small> @enderror
                </div>

                <!-- Année d’adhésion -->
                <div class="field">
                <label for="annee_adhesion">Année d’adhésion <span>*</span></label>
                <div class="input">
                    <i class="fa-solid fa-calendar-days"></i>
                    <input
                    id="annee_adhesion"
                    name="annee_adhesion"
                    type="number"
                    min="2020"
                    max="{{ date('Y') + 1 }}"
                    value="{{ old('annee_adhesion', date('Y')) }}"
                    required
                    >
                </div>
                @error('annee_adhesion') <small class="msg error">{{ $message }}</small> @enderror
                </div>


                <!-- Département -->
                <div class="field">
                    <label for="iddep">Département <span>*</span></label>
                    <div class="input">
                        <i class="fa-solid fa-building"></i>
                        <select id="iddep" name="iddep" required>
                            <option value="">Sélectionner</option>
                            @foreach($departements as $dep)
                                <option value="{{ $dep->iddep }}"
                                    {{ old('iddep') == $dep->iddep ? 'selected' : '' }}>
                                    {{ $dep->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('iddep') <small class="msg error">{{ $message }}</small> @enderror
                </div>

                <!-- Pays -->
                <div class="field">
                    <label for="idpays">Pays <span>*</span></label>
                    <div class="input">
                        <i class="fa-solid fa-globe"></i>
                        <select id="idpays" name="idpays" required>
                            <option value="">Sélectionner</option>
                            @foreach($pays as $p)
                                <option value="{{ $p->idpays }}"
                                    {{ old('idpays') == $p->idpays ? 'selected' : '' }}>
                                    {{ $p->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('idpays') <small class="msg error">{{ $message }}</small> @enderror
                </div>

                <!-- Téléphone -->
                <div class="field">
                    <label for="telephone">Téléphone <span>*</span></label>
                    <div class="input">
                        <i class="fa-solid fa-phone"></i>
                        <input
                            id="telephone"
                            name="telephone"
                            type="text"
                            value="{{ old('telephone') }}"
                            placeholder="Ex: +216 12 345 678"
                            required
                        >
                    </div>
                    @error('telephone') <small class="msg error">{{ $message }}</small> @enderror
                </div>

                <!-- Email -->
                <div class="field full">
                    <label for="email">Email <span>*</span></label>
                    <div class="input">
                        <i class="fa-solid fa-envelope"></i>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="ex: nom@exemple.com"
                            required
                        >
                    </div>
                    @error('email') <small class="msg error">{{ $message }}</small> @enderror
                </div>

                <!-- Adresse -->
                <div class="field full">
                    <label for="adresse">Adresse</label>
                    <div class="input textarea">
                        <i class="fa-solid fa-location-dot"></i>
                        <textarea
                            id="adresse"
                            name="adresse"
                            rows="3"
                            placeholder="Votre adresse (optionnel)"
                        >{{ old('adresse') }}</textarea>
                    </div>
                    @error('adresse') <small class="msg error">{{ $message }}</small> @enderror
                </div>

                <!-- Boutons -->
                <div class="actions full">
                    <button type="submit" class="btn primary" id="btn-submit">
                        <span>Créer mon compte</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>

                    <p class="hint">
                        Déjà inscrit ?
                        <a href="{{ route('login') }}">Se connecter</a>
                    </p>
                </div>

            </form>
        </div>
    </section>
</main>
@endsection
