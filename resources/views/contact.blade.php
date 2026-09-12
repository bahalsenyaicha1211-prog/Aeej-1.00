@extends('layouts.public')

@section('title', 'Équipe & Contact - AEEJ')

@section('styles')
    <link rel="stylesheet" href="{{ asset_v('css/contact.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset_v('js/contact.js') }}" defer></script>
@endsection

@section('content')
<main class="contact-page">

    <section class="ctcHero reveal">
        <span class="ctcKicker">AEEJ • Contact</span>
        <h1>Parlons-en</h1>
        <p>Une question, une idée de partenariat, besoin d'aide ? L'équipe du bureau est joignable directement, ou écrivez-nous via le formulaire ci-dessous.</p>
    </section>

    <div class="contact-container">
        <section class="team-grid">
            @forelse($equipe as $personne)
                <article class="member-card reveal {{ $personne->is_highlighted ? 'is-featured' : '' }}">
                    @if($personne->is_highlighted)
                        <span class="featured-badge">★ {{ $personne->poste }}</span>
                    @endif
                    <div class="member-photo">
                        <img loading="lazy" src="{{ $personne->photo_url }}" alt="{{ $personne->poste }} AEEJ">
                    </div>
                    <div class="member-info">
                        <h3>{{ $personne->nom }}</h3>
                        <span class="role">{{ $personne->poste }}</span>
                        <div class="member-contact">
                            @if($personne->telephone)
                                <a href="tel:{{ preg_replace('/\s+/', '', $personne->telephone) }}"><x-icon name="phone"/> {{ $personne->telephone }}</a>
                            @endif
                            @if($personne->email)
                                <a href="mailto:{{ $personne->email }}"><x-icon name="mail"/> {{ $personne->email }}</a>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="team-empty">L'équipe de contact sera bientôt affichée ici.</div>
            @endforelse
        </section>

        <aside class="contact-sidebar reveal">
            <div class="sticky-info">
                <h3>Contact direct</h3>
                <p>Pour toute question officielle ou partenariat :</p>

                <div class="info-item">
                    <x-icon name="mail"/>
                    <div>
                        <strong>Email de l'association</strong>
                        <a href="mailto:aeejendouba@gmail.com">aeejendouba@gmail.com</a>
                    </div>
                </div>

                <div class="info-item">
                    <x-icon name="map-pin"/>
                    <div>
                        <strong>Siège social</strong>
                        <span>Jendouba, Tunisie</span>
                    </div>
                </div>

                <div class="social-links">
                    <a href="https://www.facebook.com/aee.jendouba?mibextid=rS40aB7S9Ucbxw6v" class="fb" aria-label="Facebook"><x-icon name="facebook"/></a>
                    <a href="https://www.instagram.com/aee.jendouba?igsh=ZjFhbGc4YmoyYm1m" class="insta" aria-label="Instagram"><x-icon name="instagram"/></a>
                    <a href="https://www.tiktok.com/@aeejendouba.offici?_r=1&_t=ZN-93JsrHHCPSR" class="tiktok" aria-label="TikTok"><x-icon name="tiktok"/></a>
                </div>
            </div>
        </aside>

        <section class="contact-form-section reveal">
            <h3>Envoyez-nous un message</h3>
            <p>Une question, une suggestion ? Écrivez-nous, nous vous répondrons rapidement.</p>

            @if(session('success'))
                <div class="cf-alert cf-alert--success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="cf-alert cf-alert--error">
                    <strong>Veuillez corriger les erreurs :</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="cf-form" action="{{ route('contact.store') }}" method="POST" novalidate>
                @csrf

                <div class="cf-field">
                    <label for="cf-prenom">Prénom *</label>
                    <input id="cf-prenom" name="prenom" type="text" value="{{ old('prenom') }}" required>
                </div>

                <div class="cf-field">
                    <label for="cf-nom">Nom *</label>
                    <input id="cf-nom" name="nom" type="text" value="{{ old('nom') }}" required>
                </div>

                <div class="cf-field">
                    <label for="cf-email">Email *</label>
                    <input id="cf-email" name="email" type="email" value="{{ old('email') }}" required>
                </div>

                <div class="cf-field">
                    <label for="cf-telephone">Téléphone</label>
                    <input id="cf-telephone" name="telephone" type="text" value="{{ old('telephone') }}">
                </div>

                <div class="cf-field cf-field--full">
                    <label for="cf-message">Message *</label>
                    <textarea id="cf-message" name="message" rows="5" required>{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="cf-submit">Envoyer le message</button>
            </form>
        </section>
    </div>
</main>
@endsection
