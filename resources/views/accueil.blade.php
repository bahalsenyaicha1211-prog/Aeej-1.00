@extends('layouts.public')

@section('title', 'Accueil - AEEJ')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/acceuil.css') }}?v={{ filemtime(public_path('css/acceuil.css')) }}">
@endsection

@section('scripts')
  <script src="{{ asset('js/acceuil.js') }}?v={{ filemtime(public_path('js/acceuil.js')) }}" defer></script>
@endsection

@section('content')
<main class="home">

  {{-- HERO --}}
  <section class="home__hero">
    <div class="slider" aria-label="Slider AEEJ">
      <img src="{{ asset('images/img1.jpg') }}" alt="AEEJ 1" class="slide active">
      <img src="{{ asset('images/img2.JPG') }}" alt="AEEJ 2" class="slide">
      <img src="{{ asset('images/img3.JPG') }}" alt="AEEJ 3" class="slide">
      <img src="{{ asset('images/img4.JPG') }}" alt="AEEJ 4" class="slide">
      <img src="{{ asset('images/img5.JPG') }}" alt="AEEJ 5" class="slide">
      <img src="{{ asset('images/img6.JPG') }}" alt="AEEJ 6" class="slide">
      <img src="{{ asset('images/img7.JPG') }}" alt="AEEJ 7" class="slide">
      <img src="{{ asset('images/img8.JPG') }}" alt="AEEJ 8" class="slide">
      <img src="{{ asset('images/img9.JPG') }}" alt="AEEJ 9" class="slide">
      <img src="{{ asset('images/img10.jpg') }}" alt="AEEJ 10" class="slide">
      <img src="{{ asset('images/img11.JPG') }}" alt="AEEJ 11" class="slide">
      <img src="{{ asset('images/img12.jpeg') }}" alt="AEEJ 12" class="slide">
      <img src="{{ asset('images/img13.JPG') }}" alt="AEEJ 13" class="slide">
      <img src="{{ asset('images/img14.JPG') }}" alt="AEEJ 14" class="slide">
      
    
    </div>

    {{-- Overlay + texte dynamique --}}
    <div class="homeHeroOverlay"></div>

    <div class="homeHeroTextWrap">
      <div class="homeHeroBadge">AEEJ • Jendouba</div>
      <h1 id="texte-sur-image" class="homeHeroTitle"></h1>
      <p class="homeHeroSub">
        Union, solidarité et intégration pour les étudiants étrangers.
      </p>

      <div class="homeHeroActions">
        <a class="homeBtn homeBtn--primary" href="{{ route('inscription') }}">S’inscrire</a>
        <a class="homeBtn homeBtn--ghost" href="{{ route('apropos') }}">Découvrir l’association</a>
      </div>
    </div>
  </section>

  {{-- STATS --}}
  <section class="homeSection homeSection--stats">
    <div class="homeSectionHead">
      <h2 class="homeH2">Statistiques AEEJ</h2>
      <p class="homeP">Un aperçu rapide de la communauté et de nos activités.</p>
    </div>

    <div class="dashboard-container">
      {{-- 1: Membres --}}
      <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #16a34a, #22c55e);">
          <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-content">
          <h3 class="stat-number" data-target="{{ $membresCount ?? 0 }}">0</h3>
          <p class="stat-label">Membres inscrits</p>
          <div class="stat-trend">
            <i class="fa-solid fa-circle-check"></i>
            <span>Communauté active</span>
          </div>
        </div>
      </div>

      {{-- 2: Inscriptions récentes (mois en cours) --}}
      <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #0ea5e9, #22c55e);">
          <i class="fa-solid fa-user-plus"></i>
        </div>
        <div class="stat-content">
          <h3 class="stat-number" data-target="{{ $inscriptionsRecent ?? 0 }}">0</h3>
          <p class="stat-label">Inscriptions (ce mois)</p>
          <div class="stat-trend">
            <i class="fa-solid fa-calendar"></i>
            <span>Mois en cours</span>
          </div>
        </div>
      </div>

      {{-- 3: Bureau (remplace Satisfaction) --}}
      <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #22c55e, #a3e635);">
          <i class="fa-solid fa-people-group"></i>
        </div>
        <div class="stat-content">
          <h3 class="stat-number" data-target="{{ $bureauCount ?? 0 }}">0</h3>
          <p class="stat-label">Membres du bureau</p>
          <div class="stat-trend">
            <i class="fa-solid fa-shield-heart"></i>
            <span>Organisation</span>
          </div>
        </div>
      </div>

      {{-- 4: Départements --}}
      <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #34d399);">
          <i class="fa-solid fa-building"></i>
        </div>
        <div class="stat-content">
          <h3 class="stat-number" data-target="{{ $departementsCount ?? 0 }}">0</h3>
          <p class="stat-label">Départements</p>
          <div class="stat-trend">
            <i class="fa-solid fa-check"></i>
            <span>Référencés</span>
          </div>
        </div>
      </div>

      {{-- 5: Activités --}}
      <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #fde047);">
          <i class="fa-solid fa-calendar-check"></i>
        </div>
        <div class="stat-content">
          <h3 class="stat-number" data-target="{{ $activitesCount ?? 0 }}">0</h3>
          <p class="stat-label">Activités</p>
          <div class="stat-trend">
            <i class="fa-solid fa-bolt"></i>
            <span>Cette année</span>
          </div>
        </div>
      </div>

      {{-- 6: Pays --}}
      <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #a78bfa, #22c55e);">
          <i class="fa-solid fa-globe"></i>
        </div>
        <div class="stat-content">
          <h3 class="stat-number" data-target="{{ $paysCount ?? 0 }}">0</h3>
          <p class="stat-label">Pays représentés</p>
          <div class="stat-trend">
            <i class="fa-solid fa-earth-africa"></i>
            <span>Diversité</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- ABOUT --}}
  <section class="homeSection">
    <div class="aboutCard">
      <div class="aboutCard__left">
        <div class="aboutBadge">Depuis 2005</div>
        <h2 class="aboutTitle">À propos de l’AEEJ</h2>
        <p class="aboutText">
          L’Association des Étudiants Étrangers à Jendouba (AEEJ) rassemble des étudiants
          venus de différents horizons, avec un objectif commun : réussir leurs études en Tunisie.
        </p>
        <p class="aboutText">
          Nous créons un cadre d’union, d’entraide et d’échanges culturels pour faciliter l’intégration
          et améliorer la vie universitaire et sociale.
        </p>

        <div class="aboutMiniGrid">
          <div class="aboutMini">
            <div class="aboutMini__icon"><i class="fa-solid fa-handshake-angle"></i></div>
            <div>
              <div class="aboutMini__h">Solidarité</div>
              <div class="aboutMini__p">Un réseau d’entraide concret.</div>
            </div>
          </div>

          <div class="aboutMini">
            <div class="aboutMini__icon"><i class="fa-solid fa-graduation-cap"></i></div>
            <div>
              <div class="aboutMini__h">Accompagnement</div>
              <div class="aboutMini__p">Orientation et suivi.</div>
            </div>
          </div>

          <div class="aboutMini">
            <div class="aboutMini__icon"><i class="fa-solid fa-masks-theater"></i></div>
            <div>
              <div class="aboutMini__h">Culture</div>
              <div class="aboutMini__p">Activités et rencontres.</div>
            </div>
          </div>

          <div class="aboutMini">
            <div class="aboutMini__icon"><i class="fa-solid fa-rocket"></i></div>
            <div>
              <div class="aboutMini__h">Développement</div>
              <div class="aboutMini__p">Leadership & employabilité.</div>
            </div>
          </div>
        </div>
      </div>

      <div class="aboutCard__right">
        <div class="aboutHighlight">
          <div class="aboutHighlight__h">Notre engagement</div>
          <div class="aboutHighlight__p">
            Accueillir, informer, accompagner et créer des moments forts qui rapprochent les étudiants.
          </div>
        </div>

        <div class="aboutQuotes">
          <div class="quote">« L’unité dans la diversité est notre plus grande force. »</div>
          <div class="quote">« Étudier loin de chez soi, mais jamais seul. »</div>
          <div class="quote">« Chaque culture éclaire Jendouba d’une lumière unique. »</div>
        </div>
      </div>
    </div>
  </section>

</main>
@endsection
