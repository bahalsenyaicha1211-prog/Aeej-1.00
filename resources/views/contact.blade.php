@extends('layouts.public')

@section('title', 'Contact - AEEJ')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/contact.js') }}" defer></script>
@endsection

@section('content')
    <main class="conteneur2">
        <h1>Contactez-nous</h1>
        
        <!-- Logo des plateformes -->
        <section class="contact-info">
            <h3>Contactez-nous</h3>
            <ul>
                <li><a href="mailto:aeejendouba@gmail.com" title="Envoyer un mail"><i class="fas fa-envelope"></i> Mail</a></li>
            </ul>
        </section>

        <!-- Formulaire de contact avec protection CSRF -->
        <section class="contact-form">
            <form action="{{ route('contact.store') }}" method="POST">
                @csrf
                
                <div class="input-group">
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" placeholder="Entrez votre nom" required>
                    @error('nom')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Entrez votre prénom" required>
                    @error('prenom')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="email">E-mail :</label>
                    <input type="email" id="email" name="email" placeholder="Entrez votre e-mail" required>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="tel">Téléphone :</label>
                    <input type="tel" id="tel" name="telephone" placeholder="Entrez votre numéro de téléphone">
                    @error('telephone')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="message">Message :</label>
                    <textarea id="message" name="message" placeholder="Écrivez votre message ici" required></textarea>
                    @error('message')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">Envoyer</button>
            </form>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </section>
    </main>
@endsection

