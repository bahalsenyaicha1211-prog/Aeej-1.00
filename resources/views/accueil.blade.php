
@extends('layouts.public')


@section('title', 'Accueil - AEEJ')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/acceuil.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('js/acceuil.js') }}" defer></script>
@endsection

@section('content')
    <!-- Slider d'images -->
    <section class="section1">
        <div class="slider">
            <img src="{{ asset('images/image3.JPG') }}" alt="Image 1" class="slide active">
            <img src="{{ asset('images/imag2.JPG') }}" alt="Image 2" class="slide">
            <img src="{{ asset('images/image1.JPG') }}" alt="Image 3" class="slide">
            <img src="{{ asset('images/image4.JPG') }}" alt="Image 4" class="slide">
        </div>
        <div id="texte-sur-image"></div>
    </section>

    <!-- Section Dashboard Statistiques -->
    <section class="dashboard-section">
        <h2 class="dashboard-title"><i class="fa-solid fa-chart-line"></i> Statistiques de l'AEEJ</h2>
        <div class="dashboard-container">
            <!-- Carte 1: Membres totaux -->
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #0055cc, #00a0ff);">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" data-target="{{ $membresCount ?? 1250 }}">0</h3>
                    <p class="stat-label">Membres inscrits</p>
                    <div class="stat-trend">
                        <i class="fa-solid fa-arrow-up"></i>
                        <span>+12% ce mois</span>
                    </div>
                </div>
            </div>

            <!-- Carte 2: Inscriptions récentes -->
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #00bfa5, #00e5cc);">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" data-target="{{ $inscriptionsRecent ?? 48 }}">0</h3>
                    <p class="stat-label">Inscriptions récentes</p>
                    <div class="stat-trend">
                        <i class="fa-solid fa-arrow-up"></i>
                        <span>Ce mois-ci</span>
                    </div>
                </div>
            </div>

            <!-- Carte 3: Départements -->
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ff6b6b, #ff8787);">
                    <i class="fa-solid fa-building"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" data-target="{{ $departementsCount ?? 5 }}">0</h3>
                    <p class="stat-label">Départements</p>
                    <div class="stat-trend">
                        <i class="fa-solid fa-check"></i>
                        <span>Actifs</span>
                    </div>
                </div>
            </div>

            <!-- Carte 4: Activités -->
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #feca57, #ffd93d);">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" data-target="{{ $activitesCount ?? 32 }}">0</h3>
                    <p class="stat-label">Activités organisées</p>
                    <div class="stat-trend">
                        <i class="fa-solid fa-calendar"></i>
                        <span>Cette année</span>
                    </div>
                </div>
            </div>

            <!-- Carte 5: Pays représentés -->
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #a55eea, #c084fc);">
                    <i class="fa-solid fa-globe"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" data-target="{{ $paysCount ?? 15 }}">0</h3>
                    <p class="stat-label">Pays représentés</p>
                    <div class="stat-trend">
                        <i class="fa-solid fa-earth-africa"></i>
                        <span>Diversité culturelle</span>
                    </div>
                </div>
            </div>

            <!-- Carte 6: Taux de satisfaction -->
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #26de81, #20bf6b);">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" data-target="94">0</h3>
                    <p class="stat-label">% Satisfaction</p>
                    <div class="stat-trend">
                        <i class="fa-solid fa-heart"></i>
                        <span>Très satisfait</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section À propos -->
    <section>
        <div class="Apropos">
            <h2>A propos de nous</h2>
            <p>Créée en 2005, l'Association des Étudiants Étrangers à Jendouba (AEEJ) 
            est une organisation dynamique qui rassemble des étudiants venus de différents
             horizons et pays partageant un même objectif : poursuivre leurs études supérieures en Tunisie,
              dans la région de Jendouba.</p>
            <p>
            L'AEEJ se veut avant tout un espace d'union, de solidarité et d'échange culturel, 
            où chaque étudiant étranger trouve accueil, 
            accompagnement et intégration au sein de la vie universitaire et sociale tunisienne.
            </p>      
        </div>
    </section>
    
    <!-- Nos Objectifs -->
    <h2><i class="fa-solid fa-bullseye"></i> Nos Objectifs</h2>
    <div class="contenu-carte">
        <div class="carte">
            <div class="icon"><i class="fa-solid fa-graduation-cap"></i></div>
            <h3>Accompagnement académique</h3>
            <p>Aider chaque étudiant étranger à réussir son parcours universitaire à Jendouba.</p>
        </div>
       
        <div class="carte">
            <div class="icon"><i class="fa-solid fa-handshake-angle"></i></div>
            <h3>Solidarité</h3>
            <p>Créer un esprit d'entraide et de cohésion entre étudiants venus de divers horizons.</p>
        </div>

        <div class="carte">
            <div class="icon"><i class="fa-solid fa-rocket"></i></div>
            <h3>Développement</h3>
            <p>Encourager l'excellence, le leadership et l'intégration professionnelle.</p>
        </div>
    </div> 

    <!-- Mission -->
    <h2><i class="fa-solid fa-briefcase"></i> Notre Mission</h2>
    <div class="contenu-carte">
        <div class="carte">
            <div class="icon"><i class="fa-solid fa-compass"></i></div>
            <h3>Orientation</h3>
            <p>Accueillir, informer et accompagner les nouveaux étudiants dès leur arrivée.</p>
        </div>

        <div class="carte">
            <div class="icon"><i class="fa-solid fa-masks-theater"></i></div>
            <h3>Culture</h3>
            <p>Organiser des activités qui favorisent le partage et la découverte des cultures.</p>
        </div>

        <div class="carte">
            <div class="icon"><i class="fa-solid fa-comments"></i></div>
            <h3>Dialogue</h3>
            <p>Renforcer les liens entre les étudiants étrangers et la communauté locale.</p>
        </div>
    </div>

    <!-- Motivation -->
    <h2><i class="fa-solid fa-fire-flame-curved"></i> Notre Motivation</h2>
    <div class="contenu-carte">
        <div class="carte"><p>"L'unité dans la diversité est notre plus grande force."</p></div>
        <div class="carte"><p>"Étudier loin de chez soi, mais jamais seul."</p></div>
        <div class="carte"><p>"Chaque culture éclaire Jendouba d'une lumière unique."</p></div>
    </div>
@endsection

