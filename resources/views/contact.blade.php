@extends('layouts.public')

@section('title', 'Équipe & Contact - AEEJ')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@section('content')
<main class="contact-page">
    
    <div class="contact-container">
        <section class="team-grid">
            <!--President-->
            <article class="member-card admin-gold">
                <div class="member-photo">
                    <img src="{{ asset('images/team/president.jpg') }}" alt="Président AEEJ">
                </div>
                <div class="member-info">
                    <h3>MOHADED DIANE</h3>
                    <span class="role">Président</span>
                    <div class="member-contact">
                        <a href="tel:+21656464039"><i class="fas fa-phone"></i> +216 56 464 039</a><br>
                        <a href="mailto:dianemohamed0701@gmail.com"><i class="fas fa-envelope"></i>dianemohamed0701@gmail.com</a>
                    </div>
                </div>
            </article>
            <!--Sécrétaire générale-->
            <article class="member-card">
                <div class="member-photo">
                    <img src="{{ asset('images/team/secretaire.jpg') }}" alt="Secrétaire Général">
                </div>
                <div class="member-info">
                    <h3>AHMED AKRAM</h3>
                    <span class="role">Secrétaire Général</span>
                    <div class="member-contact">
                        <a href="tel:+21656660514"><i class="fas fa-phone"></i> +216 56 660 514</a><br>
                        <a href="mailto:Ahmed390akram@gmail.com"><i class="fas fa-envelope"></i> Ahmed390akram@gmail.com</a>
                    </div>
                </div>
            </article>

            <!--Chargé de la communication-->
            <article class="member-card">
                <div class="member-photo">
                    <img src="{{ asset('images/team/chargercom.jpg') }}" alt="Chargé de la Communication">
                </div>
                <div class="member-info">
                    <h3>ALSENY BAH</h3>
                    <span class="role">Chargée de la Communication</span>
                    <div class="member-contact">
                        <a href="tel:+21653877709"><i class="fas fa-phone"></i> +216 53 877 709</a>
                        <a href="mailto:bahalseny.aicha1211@gmail.com"><i class="fas fa-envelope"></i> bahalseny.aicha1211@gmail.com</a>
                    </div>
                </div>
            </article>

            </section>

        <aside class="contact-sidebar">
            <div class="sticky-info">
                <h3>Contact Direct</h3>
                <p>Pour toute question officielle ou partenariat :</p>
                
                <div class="info-item">
                    <i class="fas fa-envelope-open-text"></i>
                    <div>
                        <strong>Email de l'association</strong>
                        <a href="mailto:aeejendouba@gmail.com">aeejendouba@gmail.com</a>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-location-dot"></i>
                    <div>
                        <strong>Siège Social</strong>
                        <span>Jendouba, Tunisie</span>
                    </div>
                </div>

                <div class="social-links">
                    <a href="https://www.facebook.com/aee.jendouba?mibextid=rS40aB7S9Ucbxw6v" class="fb"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.instagram.com/aee.jendouba?igsh=ZjFhbGc4YmoyYm1m" class="insta"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/@aeejendouba.offici?_r=1&_t=ZN-93JsrHHCPSR" class="tiktok"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </aside>
    </div>
</main>
@endsection