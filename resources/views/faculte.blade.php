@extends('layouts.public')

@section('title', 'Notre Faculté - AEEJ')

@section('styles')
<style>
/* =========================
   FACULTÉ PAGE (scoped)
   ========================= */
.fac{
  --bg:#050812;
  --panel: rgba(255,255,255,.06);
  --border: rgba(255,255,255,.12);
  --txt:#e5e7eb;
  --muted: rgba(229,231,235,.72);
  --green:#34d399;
  --green2:#10b981;
  --shadow: 0 18px 45px rgba(0,0,0,.40);
  --r:18px;
  color: var(--txt);
  background:
    radial-gradient(900px 520px at 15% -10%, rgba(16,185,129,.20), transparent 60%),
    radial-gradient(900px 520px at 110% 20%, rgba(52,211,153,.12), transparent 55%),
    linear-gradient(180deg, #050812, #070b14);
  min-height: 100vh;
}

.fac *{box-sizing:border-box}

.fac__container{
  width:min(1120px, 92vw);
  margin: 0 auto;
  padding: 36px 0 64px;
}

.facHero{
  border-radius: 26px;
  overflow:hidden;
  border: 1px solid rgba(255,255,255,.10);
  background: rgba(255,255,255,.05);
  box-shadow: var(--shadow);
  display:grid;
  grid-template-columns: 1.1fr .9fr;
}
.facHero__left{padding: 28px;}
.facHero__right{position:relative; min-height: 320px; background: rgba(0,0,0,.15);}
.facHero__right img{width:100%; height:100%; object-fit:cover; display:block;}
.facHero__cap{
  position:absolute; left: 12px; bottom: 12px; right: 12px;
  padding: 10px 12px;
  border-radius: 16px;
  background: rgba(0,0,0,.35);
  border: 1px solid rgba(255,255,255,.12);
  backdrop-filter: blur(10px);
  font-weight: 800;
  font-size: 13px;
}

.facKicker{
  display:inline-flex; align-items:center; gap:10px;
  padding: 8px 12px;
  border-radius: 999px;
  background: rgba(16,185,129,.14);
  border: 1px solid rgba(16,185,129,.25);
  color: rgba(167,243,208,.95);
  font-weight: 800;
  font-size: 13px;
}
.facHero h1{
  margin: 14px 0 10px;
  font-size: clamp(24px, 3vw, 40px);
  line-height: 1.1;
  font-weight: 950;
}
.facHero p{
  margin: 0;
  color: rgba(229,231,235,.84);
  line-height: 1.7;
  font-size: 14px;
}
.facHero__quote{
  margin-top: 14px;
  padding: 12px 14px;
  border-radius: 18px;
  background: rgba(0,0,0,.28);
  border: 1px solid rgba(255,255,255,.10);
  font-weight: 800;
  transition: opacity 260ms ease, transform 260ms ease;
}

.facGrid{
  margin-top: 14px;
  display:grid;
  grid-template-columns: repeat(12, minmax(0,1fr));
  gap: 12px;
}
.facCard{
  grid-column: span 6;
  border: 1px solid rgba(255,255,255,.12);
  background: rgba(255,255,255,.05);
  border-radius: 22px;
  box-shadow: var(--shadow);
  overflow:hidden;
}
.facCard__body{padding: 18px;}
.facTitle{margin:0 0 8px; font-size:18px; font-weight: 950;}
.facText{margin:0; color: rgba(229,231,235,.80); line-height:1.7; font-size:14px;}
.facList{margin: 12px 0 0; padding-left: 18px; color: rgba(229,231,235,.80); line-height:1.7; font-size:14px;}

.facMedia{min-height: 230px; background: rgba(0,0,0,.15); position:relative;}
.facMedia img{width:100%; height:100%; object-fit:cover; display:block;}
.facMedia__cap{
  position:absolute; left: 12px; bottom: 12px; right: 12px;
  padding: 10px 12px;
  border-radius: 16px;
  background: rgba(0,0,0,.35);
  border: 1px solid rgba(255,255,255,.12);
  backdrop-filter: blur(10px);
  font-weight: 800;
  font-size: 13px;
}

.reveal{
  opacity:0;
  transform: translateY(10px);
  transition: opacity 520ms ease, transform 520ms ease;
}
.reveal.is-in{opacity:1; transform: translateY(0);}

@media (max-width: 980px){
  .facHero{grid-template-columns: 1fr;}
  .facCard{grid-column: span 12;}
}
@media (max-width: 640px){
  .fac__container{padding: 18px 0 52px;}
  .facHero{border-radius: 18px;}
  .facHero__left{padding: 18px;}
}
</style>
@endsection

@section('scripts')
<script>
(() => {
  // ===== REVEAL ON SCROLL =====
  function initReveal(){
    const items = document.querySelectorAll('.reveal');
    if (!items.length) return;

    const obs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting){
          e.target.classList.add('is-in');
          obs.unobserve(e.target);
        }
      });
    }, {threshold: 0.18});

    items.forEach(el => obs.observe(el));
  }

  // ===== QUOTE ROTATION =====
  const quote = document.querySelector('[data-fac-quote]');
  const quotes = [
    "Un cadre de formation en droit, économie et gestion.",
    "Un environnement ouvert, inclusif et orienté excellence.",
    "Le cœur académique des étudiants de l’AEEJ."
  ];
  let q = 0;

  function tickQuote(){
    if (!quote) return;
    quote.style.opacity = "0";
    quote.style.transform = "translateY(6px)";
    setTimeout(() => {
      quote.textContent = quotes[q];
      quote.style.opacity = "1";
      quote.style.transform = "translateY(0)";
      q = (q + 1) % quotes.length;
    }, 240);
  }

  document.addEventListener('DOMContentLoaded', () => {
    initReveal();
    tickQuote();
    setInterval(tickQuote, 3800);
  });
})();
</script>
@endsection

@section('content')
<main class="fac">
  <div class="fac__container">

    {{-- HERO --}}
    <section class="facHero reveal">
      <div class="facHero__left">
        <span class="facKicker">Notre Université</span>
        <h1>Faculté des Sciences Juridiques, Économiques et de Gestion de Jendouba</h1>
        <p>
          La FSJEG de Jendouba est le cadre académique de nombreux membres de l’AEEJ.
          Elle accompagne les étudiants dans leur formation, leur progression et leur insertion,
          tout en favorisant la vie universitaire et associative.
        </p>
        <div class="facHero__quote" data-fac-quote></div>
      </div>

      <div class="facHero__right">
        {{-- ===============================
           IMAGE PRINCIPALE (bâtiment / entrée / campus)
           Catégorie : "Façade / Campus"
           =============================== --}}
        <img src="{{ asset('images/faculte/fac1.jpg') }}" alt="Faculté de Jendouba">
        <div class="facHero__cap">Un campus au cœur de la vie étudiante</div>
      </div>
    </section>

    {{-- CARDS --}}
    <section class="facGrid">
      <article class="facCard reveal">
        <div class="facCard__body">
          <h2 class="facTitle">Mission académique</h2>
          <p class="facText">
            Former des cadres compétents en droit, économie et gestion, avec rigueur et esprit critique.
            La faculté met l’accent sur la qualité de la formation et l’ouverture.
          </p>
          <ul class="facList">
            <li>Formation solide et structurée</li>
            <li>Développement de compétences professionnelles</li>
            <li>Encouragement de l’engagement étudiant</li>
          </ul>
        </div>
      </article>

      <article class="facCard reveal">
        <div class="facMedia">
          {{-- ===============================
             IMAGE "ETUDES" (salle de cours / bibliothèque)
             Catégorie : "Études"
             =============================== --}}
          <img src="{{ asset('images/faculte/fac2.jpg') }}" alt="Études à la faculté">
          <div class="facMedia__cap">Apprendre, évoluer, réussir</div>
        </div>
      </article>

      <article class="facCard reveal">
        <div class="facMedia">
          {{-- ===============================
             IMAGE "VIE UNIVERSITAIRE" (club, événement, activité)
             Catégorie : "Vie universitaire"
             =============================== --}}
          <img src="{{ asset('images/faculte/fac5.jpeg') }}" alt="Vie universitaire">
          <div class="facMedia__cap">Une vie étudiante dynamique</div>
        </div>
      </article>

      <article class="facCard reveal">
        <div class="facCard__body">
          <h2 class="facTitle">Accueil des étudiants étrangers</h2>
          <p class="facText">
            La faculté accueille des étudiants de différentes nationalités.
            L’AEEJ contribue à l’intégration, à l’orientation et au bien-être des étudiants étrangers,
            en créant un esprit de famille et de solidarité.
          </p>
          <ul class="facList">
            <li>Accompagnement et intégration</li>
            <li>Communication et entraide</li>
            <li>Activités culturelles et éducatives</li>
          </ul>
        </div>
      </article>
    </section>

  </div>
</main>
@endsection
