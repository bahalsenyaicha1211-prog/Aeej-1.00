@extends('layouts.public')


@section('title', 'Guide Étudiant - AEEJ')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/guide.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/guideEtudiant.js') }}" defer></script>
@endsection

@section('content')
    <!-- Section Accès rapide -->
    <section class="section" id="services">
        <div class="container">
            <h2>Accès rapide</h2>
            <p class="lead">Les sujets les plus demandés par les étudiants. Cliquez pour voir les guides détaillés.</p>
            <div class="cards">
                <div class="card">
                    <div class="icon"><i class="fa-solid fa-seedling"></i></div>
                    <h3>Intégration</h3>
                    <p>Premiers pas à Tunis: carte SIM, transport, santé, repères utiles.</p>
                    <a href="#guide-sejour" onclick="showContent('integration')">Ouvrir le guide →</a>
                </div>
                <div class="card">
                    <div class="icon"><i class="fa-solid fa-passport"></i></div>
                    <h3>Séjour</h3>
                    <p>Renouvellement, première demande, documents nécessaires.</p>
                    <a href="#guide-sejour" onclick="showContent('sejour')">Démarches de séjour →</a>
                </div>
                <div class="card">
                    <div class="icon"><i class="fa-solid fa-house"></i></div>
                    <h3>Logement</h3>
                    <p>Quartiers, loyers moyens, contrat, colocation, astuces.</p>
                    <a href="#guide-sejour" onclick="showContent('logement')">Trouver un logement →</a>
                </div>
                <div class="card">
                    <div class="icon"><i class="fa-solid fa-coins"></i></div>
                    <h3>Finances</h3>
                    <p>Banques, transferts, bourses, dépenses à prévoir.</p>
                    <a href="#guide-sejour" onclick="showContent('finance')">Gérer son budget →</a>
                </div>
            </div>
        </div>
    </section>

    <section id="guide-sejour">
        <h1>Guide Étudiant</h1>
        <p>« Les démarches ne sont pas compliquées du tout : l'association vous accompagnera à chaque étape — de l'arrivée en Tunisie à l'installation complète — en vous guidant dans toutes les formalités administratives, la recherche de logement, et en vous apportant un soutien personnalisé pour que vous puissiez vous concentrer sereinement sur vos études. »</p>
        <p>« Cliquez sur les boutons ci-dessous pour découvrir l'idée qui se cache derrière chaque section. »</p>

        <!-- BOUTONS -->
        <div class="buttons">
            <button onclick="showContent('integration')">Intégration</button>
            <button onclick="showContent('sejour')">Séjour</button>
            <button onclick="showContent('logement')">Logement</button>
            <button onclick="showContent('finance')">Finance</button>
        </div>

        <!-- CONTENUS (copiés du HTML original) -->
        <div id="integration" class="content">
            <h2>Intégration</h2>
            <p>Participez aux événements associatifs, découvrez la culture tunisienne, et nouez des liens avec d'autres étudiants étrangers et locaux.</p>
            <article class="card">
                <h3><i class="fa-solid fa-sim-card"></i> Dès l'arrivée</h3>
                <ul>
                    <li>Prendre une <strong>carte SIM</strong> locale (TT, Ooredoo, Orange) et activer Internet.</li>
                    <li>Rejoindre les <strong>groupes WhatsApp/Facebook</strong> d'étudiants guinéens.</li>
                    <li>Localiser les <strong>services proches</strong> (hôpital, police, transports, ambassade/consulat).</li>
                </ul>
            </article>
            <!-- Autres articles... -->
        </div>

        <div id="sejour" class="content">
            <h2>Carte Séjour</h2>
            <h3>1. Pourquoi la carte de séjour est indispensable</h3>
            <p>Obligatoire pour tout étudiant étranger séjournant plus de 3 mois en Tunisie. Permet de rester en toute légalité.</p>
            <!-- Contenu complet... -->
        </div>

        <div id="logement" class="content">
            <h2>Logement</h2>
            <p>Vous pouvez trouver un logement via les groupes Facebook, les agences ou par bouche-à-oreille. Pensez à visiter avant de signer.</p>
            <!-- Contenu complet... -->
        </div>

        <div id="finance" class="content">
            <h2>Gestion financière</h2>
            <p>Ouvrir un compte bancaire local peut vous faciliter la vie. Prévoyez un budget mensuel pour loyer, transport, repas, et extras.</p>
            <!-- Contenu complet... -->
        </div>

        <p>Ne vous inquiétez pas : vous serez accompagné(e) à chaque étape de votre arrivée et de votre installation en Tunisie. Dès l'aéroport, l'association vous guidera dans les démarches (visa, logement, carte de séjour…), pour que vous puissiez vous concentrer sur ce qui compte vraiment : vos études.</p>
    </section>
@endsection

