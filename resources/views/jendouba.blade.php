@extends('layouts.public')

@section('title', 'Découvrir Jendouba - AEEJ')

@section('styles')
<style>
/* =========================
   JENDOUBA PAGE (scoped)
   ========================= */
.jdb{
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
    radial-gradient(900px 520px at 15% -10%, rgba(16,185,129,.22), transparent 60%),
    radial-gradient(900px 520px at 110% 20%, rgba(52,211,153,.15), transparent 55%),
    linear-gradient(180deg, #050812, #070b14);
  min-height: 100vh;
}

.jdb *{box-sizing:border-box}
.jdb a{color:inherit; text-decoration:none}

.jdb__container{
  width:min(1120px, 92vw);
  margin: 0 auto;
  padding: 36px 0 64px;
}

/* HERO */
.jdbHero{
  position:relative;
  border-radius: 26px;
  overflow:hidden;
  min-height: 420px;
  box-shadow: var(--shadow);
  border: 1px solid rgba(255,255,255,.10);
}
.jdbHero__media{
  position:absolute;
  inset:0;
  background: #0b1220;
}
.jdbHero__img{
  position:absolute;
  inset:0;
  width:100%;
  height:100%;
  object-fit: cover;
  opacity: 0;
  transition: opacity 900ms ease;
}
.jdbHero__img.is-active{opacity:1;}
.jdbHero__overlay{
  position:absolute; inset:0;
  background:
    linear-gradient(90deg, rgba(0,0,0,.60), rgba(0,0,0,.15)),
    linear-gradient(180deg, rgba(0,0,0,.15), rgba(0,0,0,.55));
}
.jdbHero__content{
  position:relative;
  padding: 36px;
  max-width: 720px;
}
.jdbKicker{
  display:inline-flex; align-items:center; gap:10px;
  padding: 8px 12px;
  border-radius: 999px;
  background: rgba(16,185,129,.14);
  border: 1px solid rgba(16,185,129,.25);
  color: rgba(167,243,208,.95);
  font-weight: 800;
  letter-spacing: .02em;
  font-size: 13px;
}
.jdbHero h1{
  margin: 14px 0 10px;
  font-size: clamp(28px, 3.4vw, 46px);
  line-height: 1.06;
  font-weight: 950;
}
.jdbHero p{
  margin: 0;
  font-size: 15px;
  line-height: 1.65;
  color: rgba(229,231,235,.86);
}
.jdbHero__text{
  margin-top: 18px;
  padding: 14px 16px;
  border-radius: 18px;
  background: rgba(0,0,0,.30);
  border: 1px solid rgba(255,255,255,.10);
  backdrop-filter: blur(10px);
  font-weight: 800;
  font-size: clamp(14px, 2.2vw, 18px);
  transition: opacity 260ms ease, transform 260ms ease;
}

.jdbHero__actions{
  margin-top: 18px;
  display:flex; gap:10px; flex-wrap:wrap;
}
.jdbBtn{
  display:inline-flex; align-items:center; justify-content:center;
  padding: 11px 14px;
  border-radius: 14px;
  border: 1px solid rgba(255,255,255,.14);
  background: rgba(255,255,255,.06);
  color: var(--txt);
  font-weight: 900;
  transition: transform .12s ease, background .12s ease, border-color .12s ease;
}
.jdbBtn:hover{
  transform: translateY(-1px);
  background: rgba(255,255,255,.08);
  border-color: rgba(255,255,255,.18);
}
.jdbBtn--primary{
  background: linear-gradient(135deg, rgba(16,185,129,.30), rgba(52,211,153,.12));
  border-color: rgba(16,185,129,.45);
}

/* SECTIONS */
.jdbSection{margin-top: 18px;}
.jdbGrid{
  display:grid;
  grid-template-columns: 1.2fr .8fr;
  gap: 14px;
  align-items: stretch;
}
.jdbCard{
  border: 1px solid rgba(255,255,255,.12);
  background: rgba(255,255,255,.05);
  border-radius: 22px;
  box-shadow: var(--shadow);
  overflow:hidden;
}
.jdbCard__body{padding: 18px;}
.jdbTitle{
  margin: 0 0 8px;
  font-size: 18px;
  font-weight: 950;
  letter-spacing: .2px;
}
.jdbText{
  margin: 0;
  color: rgba(229,231,235,.80);
  line-height: 1.7;
  font-size: 14px;
}
.jdbList{
  margin: 12px 0 0;
  padding-left: 18px;
  color: rgba(229,231,235,.80);
  line-height: 1.7;
  font-size: 14px;
}
.jdbMedia{
  position: relative;
  min-height: 220px;
  background: rgba(0,0,0,.18);
}
.jdbMedia img{
  width:100%;
  height:100%;
  object-fit: cover;
  display:block;
  filter: saturate(1.06) contrast(1.02);
}
.jdbMedia__cap{
  position:absolute;
  left: 12px; bottom: 12px; right: 12px;
  padding: 10px 12px;
  border-radius: 16px;
  background: rgba(7, 75, 138, 0.35);
  border: 1px solid rgba(255,255,255,.12);
  backdrop-filter: blur(10px);
  color: rgba(229,231,235,.92);
  font-size: 13px;
  font-weight: 800;
}

/* KPI */
.jdbKpis{
  display:grid;
  grid-template-columns: repeat(12, minmax(0,1fr));
  gap: 12px;
  margin-top: 14px;
}
.jdbKpi{
  grid-column: span 3;
  padding: 14px;
  border-radius: 20px;
  border: 1px solid rgba(255,255,255,.12);
  background: rgba(255,255,255,.05);
  box-shadow: var(--shadow);
  position:relative;
  overflow:hidden;
}
.jdbKpi::before{
  content:"";
  position:absolute; left:0; top:0; bottom:0;
  width:4px;
  background: linear-gradient(180deg, rgba(16,185,129,.95), rgba(52,211,153,.55));
}
.jdbKpi__label{
  font-size: 11px;
  letter-spacing: .10em;
  text-transform: uppercase;
  color: rgba(229,231,235,.70);
}
.jdbKpi__value{
  margin-top: 10px;
  font-size: 28px;
  font-weight: 950;
}
.jdbKpi__meta{
  margin-top: 4px;
  font-size: 12px;
  color: rgba(229,231,235,.68);
}

/* Reveal */
.reveal{
  opacity: 0;
  transform: translateY(10px);
  transition: opacity 520ms ease, transform 520ms ease;
}
.reveal.is-in{
  opacity: 1;
  transform: translateY(0);
}

/* Responsive */
@media (max-width: 980px){
  .jdbGrid{grid-template-columns: 1fr; }
  .jdbKpi{grid-column: span 6;}
}
@media (max-width: 640px){
  .jdb__container{padding: 18px 0 52px;}
  .jdbHero__content{padding: 18px;}
  .jdbHero{min-height: 380px; border-radius: 18px;}
  .jdbKpi{grid-column: span 12;}
}
</style>
@endsection

@section('scripts')
<script>
(() => {
  // ===== HERO SLIDER (images) =====
  const slides = document.querySelectorAll('[data-jdb-hero-slide]');
  let s = 0;

  function tickSlide(){
    if (!slides.length) return;
    slides[s].classList.remove('is-active');
    s = (s + 1) % slides.length;
    slides[s].classList.add('is-active');
  }

  // ===== HERO TEXT ROTATION =====
  const heroText = document.querySelector('[data-jdb-hero-text]');
  const textes = [
    "Une ville verte, calme et accueillante.",
    "Entre paysages, culture et vie étudiante.",
    "Un nord-ouest tunisien à découvrir absolument."
  ];
  let t = 0;

  function tickText(){
    if (!heroText) return;
    heroText.style.opacity = "0";
    heroText.style.transform = "translateY(6px)";
    setTimeout(() => {
      heroText.textContent = textes[t];
      heroText.style.opacity = "1";
      heroText.style.transform = "translateY(0)";
      t = (t + 1) % textes.length;
    }, 240);
  }

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

  // ===== COUNTERS =====
  function animateCounter(el, target, duration = 1200){
    let cur = 0;
    const steps = Math.max(24, Math.floor(duration / 16));
    const inc = target / steps;

    const timer = setInterval(() => {
      cur += inc;
      if (cur >= target){
        el.textContent = Math.round(target);
        clearInterval(timer);
      } else {
        el.textContent = Math.round(cur);
      }
    }, 16);
  }

  function initCounters(){
    const nums = document.querySelectorAll('[data-jdb-count]');
    if (!nums.length) return;

    const obs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (!e.isIntersecting) return;
        const el = e.target;
        if (el.dataset.animated) return;
        el.dataset.animated = "1";
        const target = parseInt(el.getAttribute('data-jdb-count') || "0", 10);
        animateCounter(el, isNaN(target) ? 0 : target);
        obs.unobserve(el);
      });
    }, {threshold: 0.25});

    nums.forEach(el => obs.observe(el));
  }

  document.addEventListener('DOMContentLoaded', () => {
    // set initial slide active
    if (slides.length) slides[0].classList.add('is-active');

    tickText();
    setInterval(tickText, 3800);

    if (slides.length > 1) setInterval(tickSlide, 4500);

    initReveal();
    initCounters();
  });
})();
</script>
@endsection

@section('content')
<main class="jdb">
  <div class="jdb__container">

    {{-- HERO --}}
    <section class="jdbHero reveal">
      <div class="jdbHero__media">
        {{-- ===============================
           IMAGES HERO (paysage / vue de Jendouba)
           Remplace les src par tes images
           =============================== --}}
        <img data-jdb-hero-slide class="jdbHero__img is-active" src="{{ asset('images/jendouba/p3.jpg') }}" alt="Jendouba">
        <img data-jdb-hero-slide class="jdbHero__img" src="{{ asset('images/jendouba/v2.jpg') }}" alt="Jendouba">
        <img data-jdb-hero-slide class="jdbHero__img" src="{{ asset('images/jendouba/p4.jpg') }}" alt="Jendouba">

        <div class="jdbHero__overlay"></div>
      </div>

      <div class="jdbHero__content">
        <span class="jdbKicker">Découvrir la ville de Jendouba</span>
        <h1>Nature, culture et sérénité au nord-ouest de la Tunisie</h1>
        <p>
          Jendouba est une ville verdoyante et accueillante, entourée de paysages naturels,
          de traditions locales et d’une vie universitaire active. C’est ici que vit et évolue l’AEEJ.
        </p>

        <div class="jdbHero__text" data-jdb-hero-text></div>

        <div class="jdbHero__actions">
          <a class="jdbBtn jdbBtn--primary" href="#paysages">Explorer les paysages</a>
          <a class="jdbBtn" href="#culture">Voir l’aspect culturel</a>
        </div>
      </div>
    </section>

    {{-- KPI (valeurs indicatives : tu peux les changer) --}}
    <section class="jdbSection reveal" aria-label="Chiffres clés">
      <div class="jdbKpis">
        <div class="jdbKpi">
          <div class="jdbKpi__label">Région</div>
          
          <div class="jdbKpi__meta">Nord-Ouest tunisien</div>
        </div>
        <div class="jdbKpi">
          <div class="jdbKpi__label">Atout</div>
          <div class="jdbKpi__value" data-jdb-count="3">0</div>
          <div class="jdbKpi__meta">Nature • Culture • Études</div>
        </div>
        <div class="jdbKpi">
          <div class="jdbKpi__label">Ambiance</div>
          <div class="jdbKpi__value" data-jdb-count="100">0</div>
          <div class="jdbKpi__meta">Calme et accueillante</div>
        </div>
        <div class="jdbKpi">
          <div class="jdbKpi__label">Vie étudiante</div>
          <div class="jdbKpi__value" data-jdb-count="1">0</div>
          <div class="jdbKpi__meta">Ville universitaire</div>
        </div>
      </div>
    </section>

    {{-- SECTION 1 --}}
    <section id="paysages" class="jdbSection reveal">
      <div class="jdbGrid">
        <div class="jdbCard">
          <div class="jdbCard__body">
            <h2 class="jdbTitle">Paysages et nature</h2>
            <p class="jdbText">
              Jendouba se distingue par sa verdure, ses reliefs, ses forêts et sa proximité avec des zones naturelles
              connues dans le nord-ouest. C’est une destination idéale pour ceux qui aiment les paysages calmes et authentiques.
            </p>
            <ul class="jdbList">
              <li>Forêts et collines verdoyantes</li>
              <li>Zones rurales et panoramas naturels</li>
              <li>Climat agréable, surtout en hiver et au printemps</li>
            </ul>
          </div>
        </div>

        <div class="jdbCard">
          <div class="jdbMedia">
            {{-- ===============================
               IMAGE PAYSAGE (forêt, montagne, panorama)
               Catégorie : "Nature"
               =============================== --}}
            <img src="{{ asset('images/jendouba/p1.jpg') }}" alt="Paysage de Jendouba">
            <div class="jdbMedia__cap">Une région verte, idéale à explorer</div>
          </div>
        </div>
      </div>
    </section>

    {{-- SECTION 2 --}}
    <section id="culture" class="jdbSection reveal">
      <div class="jdbGrid">
        <div class="jdbCard">
          <div class="jdbMedia">
            {{-- ===============================
               IMAGE CULTURE (marché, artisanat, événement)
               Catégorie : "Culture"
               =============================== --}}
            <img src="{{ asset('images/jendouba/v1.jpg') }}" alt="Culture à Jendouba">
            <div class="jdbMedia__cap">Traditions, échanges et authenticité</div>
          </div>
        </div>

        <div class="jdbCard">
          <div class="jdbCard__body">
            <h2 class="jdbTitle">Culture et identité</h2>
            <p class="jdbText">
              La région porte une identité humaine chaleureuse : hospitalité, solidarité, traditions locales.
              Elle offre une expérience culturelle forte, où l’on apprend aussi à travers le vivre-ensemble.
            </p>
            <ul class="jdbList">
              <li>Vie locale riche et conviviale</li>
              <li>Événements culturels et traditions</li>
              <li>Diversité grâce à la présence étudiante</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    {{-- SECTION 3 --}}
    <section class="jdbSection reveal">
      <div class="jdbGrid">
        <div class="jdbCard">
          <div class="jdbCard__body">
            <h2 class="jdbTitle">Pourquoi visiter Jendouba ?</h2>
            <p class="jdbText">
              Si tu veux découvrir une autre facette de la Tunisie, loin du bruit, dans un décor naturel et humain,
              Jendouba est un choix évident.
            </p>
            <ul class="jdbList">
              <li>Un cadre naturel apaisant</li>
              <li>Une expérience culturelle authentique</li>
              <li>Une ville propice aux études et à la stabilité</li>
            </ul>
          </div>
        </div>

        <div class="jdbCard">
          <div class="jdbMedia">
            {{-- ===============================
               IMAGE "VISITER" (paysage + route, vue, tourisme)
               Catégorie : "Tourisme / Découverte"
               =============================== --}}
            <img src="{{ asset('images/jendouba/p5.avif') }}" alt="Visiter Jendouba">
            <div class="jdbMedia__cap">Une destination qui se vit</div>
          </div>
        </div>
      </div>
    </section>

  </div>
</main>
@endsection
