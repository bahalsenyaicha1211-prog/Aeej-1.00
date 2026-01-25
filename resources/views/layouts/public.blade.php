<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'AEEJ')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSS GLOBAL -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- CSS SPÉCIFIQUE PAGE -->
    @yield('styles')
</head>

<body>
<header>
    <div class="conteneur">
        <div class="logo">
            <a href="{{ route('accueil') }}">
                <img src="{{ asset('images/drapeau/AEEJ.png') }}" alt="Logo AEEJ">
            </a>
        </div>

        <div class="bouton-menu" id="bouton-menu">
            <span></span>
            <span></span>
            <span></span>
            <p>Menu</p>
        </div>

        <nav id="menu-navigation">
            <ul>
                <li><a href="{{ route('accueil') }}">Accueil</a></li>
                <li><a href="{{ route('apropos') }}">À propos</a></li>
                <li><a href="{{ route('guideEtudiant') }}">Guide étudiant</a></li>
                <li><a href="{{ route('bureau') }}">Bureau</a></li>
                <li><a href="{{ route('activites.public') }}">Activités</a></li>
                <li><a href="{{ route('jendouba') }}">Jendouba</a></li>
                <li><a href="{{ route('faculte') }}"> Notre Faculté</a></li>
                <li><a href="{{ route('galerie') }}" title="voir des images">Galerie</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>

                @guest
                    <li><a href="{{ route('inscription') }}">S’inscrire</a></li>
                    <li><a href="{{ route('login') }}">Connexion</a></li>
                @else
                    <li>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Déconnexion
                        </a>
                    </li>
                @endguest
            </ul>
        </nav>
    </div>
</header>

@auth
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
</form>
@endauth

<main>
    @yield('content')
</main>

<footer>
    <div class="ConteneurPied">
        <h2>Nos communautées</h2>
        <div class="drapeau">
            <ul>
                <li><img src="{{ asset('images/drapeau/Guinée.png') }}" alt="Guinée"> Guinée</li>
                <li><img src="{{ asset('images/drapeau/Mali.png') }}" alt="Mali"> Mali</li>
                <li><img src="{{ asset('images/drapeau/Comores.png') }}" alt="Comores"> Comores</li>
                <li><img src="{{ asset('images/drapeau/Tchad.png') }}" alt="Tchad"> Tchad</li>
                <li><img src="{{ asset('images/drapeau/Congo.png') }}" alt="Congo"> Congo</li>
                <li><img src="{{ asset('images/drapeau/cote.png') }}" alt="Côte d’Ivoire"> Côte d’Ivoire</li>
                <li><img src="{{ asset('images/drapeau/niger.png') }}" alt="Niger"> Niger</li>
</li>

                
            </ul>
        </div>

        <h2>Suivez-nous sur</h2>
        <div class="Resaux-sociaux">
            <a href="https://www.facebook.com/aee.jendouba?mibextid=rS40aB7S9Ucbxw6v" class="social-icon facebook" title="Visitez notre Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="https://chat.whatsapp.com/JNSlBypG7XY1nNfWzhNUhA" class="social-icon whatsapp" title="Rejoignez notre groupe WhatsApp"><i class="fab fa-whatsapp"></i></a>
            <a href="https://www.tiktok.com/@aeejendouba.offici?_r=1&_t=ZN-93JsrHHCPSR" class="social-icon tiktok" title="Visitez notre TikTok"><i class="fab fa-tiktok"></i></a>
            <a href="https://www.instagram.com/aee.jendouba?igsh=ZjFhbGc4YmoyYm1m" class="social-icon instagram" title="Visitez notre Instagram"><i class="fab fa-instagram"></i></a>
            
        </div>
    </div>

    <div class="droit-reserver">
        <p>© {{ date('Y') }} AEEJ — Tous droits réservés</p>
    </div>
</footer>

<!-- JS GLOBAL -->
<script src="{{ asset('js/style.js') }}" defer></script>

<!-- JS SPÉCIFIQUE PAGE -->
@yield('scripts')
</body>
</html>
