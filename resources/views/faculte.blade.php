@extends('layouts.public')

@section('title', 'Notre Université - AEEJ')

@section('styles')
<style>
/* =========================
   UNIVERSITÉ / FACULTÉ (scoped)
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

/* ---- Hero Université ---- */
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
.facHero__actions{
  margin-top: 16px;
  display:flex; gap:10px; flex-wrap:wrap;
}
.facBtn{
  display:inline-flex; align-items:center; justify-content:center;
  padding: 11px 14px;
  border-radius: 14px;
  border: 1px solid rgba(255,255,255,.14);
  background: rgba(255,255,255,.06);
  color: var(--txt);
  font-weight: 900;
  text-decoration:none;
  transition: transform .12s ease, background .12s ease, border-color .12s ease;
}
.facBtn:hover{ transform: translateY(-1px); background: rgba(255,255,255,.08); border-color: rgba(255,255,255,.18); }
.facBtn--primary{
  background: linear-gradient(135deg, rgba(16,185,129,.30), rgba(52,211,153,.12));
  border-color: rgba(16,185,129,.45);
}

/* Diaporama générique du héro (dossier public/images/universite/) */
.facHero__media{ position:absolute; inset:0; background:#0b1220; }
.fac-slide{
  position:absolute; inset:0; width:100%; height:100%; object-fit:cover;
  opacity:0; transition: opacity .8s ease;
}
.fac-slide.is-active{ opacity:1; }
.fac-dots{ position:absolute; z-index:3; right:12px; top:12px; display:flex; gap:6px; }
.fac-dots i{
  width:7px; height:7px; border-radius:999px; cursor:pointer;
  background: rgba(255,255,255,.45);
  box-shadow: 0 1px 3px rgba(0,0,0,.4);
  transition: background .2s ease, transform .2s ease;
}
.fac-dots i.is-on{ background:#fff; transform: scale(1.25); }

.facHero__soon{
  position:absolute; inset:0;
  display:flex; flex-direction:column; align-items:center; justify-content:center;
  gap:10px;
  text-align:center;
  padding: 24px;
  background:
    radial-gradient(600px 300px at 30% 20%, rgba(16,185,129,.18), transparent 60%),
    linear-gradient(160deg, rgba(255,255,255,.05), rgba(0,0,0,.15));
}
.facHero__soon svg{ width:40px; height:40px; opacity:.55; }
.facHero__soon span{ color: rgba(229,231,235,.62); font-weight:800; font-size:13px; max-width:220px; }

.facHero__cap{
  position:absolute; left: 12px; bottom: 12px; right: 12px;
  padding: 10px 12px;
  border-radius: 16px;
  background: rgba(0,0,0,.35);
  border: 1px solid rgba(255,255,255,.12);
  backdrop-filter: blur(10px);
  font-weight: 800;
  font-size: 13px;
  z-index: 2;
}

/* ---- Bandeau chiffres clés ---- */
.facStats{
  margin-top: 14px;
  display:grid;
  grid-template-columns: repeat(4, minmax(0,1fr));
  gap: 12px;
}
.facStat{
  border: 1px solid rgba(255,255,255,.12);
  background: rgba(255,255,255,.05);
  border-radius: 18px;
  padding: 16px;
  text-align:center;
}
.facStat b{ display:block; font-size: 22px; font-weight: 950; color: rgba(167,243,208,.95); }
.facStat span{ display:block; margin-top:4px; font-size:12px; color: var(--muted); font-weight:700; }

/* ---- Sections génériques ---- */
.facSection{ margin-top: 26px; }
.facSection__head{ margin-bottom: 14px; }
.facSection__head h2{ margin:0 0 6px; font-size: clamp(19px, 2.2vw, 26px); font-weight: 950; }
.facSection__head p{ margin:0; color: var(--muted); line-height:1.6; font-size:14px; max-width: 760px; }

/* ---- Établissements ---- */
.facEtabs{
  display:grid;
  grid-template-columns: repeat(3, minmax(0,1fr));
  gap: 12px;
}
.facEtab{
  border: 1px solid rgba(255,255,255,.12);
  background: rgba(255,255,255,.05);
  border-radius: 18px;
  overflow:hidden;
  transition: transform .16s ease, border-color .16s ease, box-shadow .16s ease;
}
.facEtab:hover{
  transform: translateY(-3px);
  border-color: rgba(255,255,255,.22);
  box-shadow: var(--shadow);
}
.facEtab--current{ border-color: rgba(16,185,129,.45); }
.facEtab__media{
  position:relative;
  height: 108px;
  background: rgba(0,0,0,.22);
  overflow:hidden;
}
.facEtab__media img{
  width:100%; height:100%; object-fit:cover; display:block;
  opacity:0; position:absolute; inset:0;
  transition: opacity .7s ease;
}
.facEtab__media img.is-active{ opacity:1; }
.facEtab__media--empty{
  display:flex; align-items:center; justify-content:center;
  color: rgba(229,231,235,.28);
}
.facEtab__media--empty svg{ width:26px; height:26px; }
.facEtab__dot{
  position:absolute; top:8px; right:8px; z-index:2;
  width:8px; height:8px; border-radius:999px;
  background: var(--green);
  box-shadow: 0 0 0 3px rgba(5,8,18,.55);
}
.facEtab__body{
  padding: 11px 13px;
  font-size: 12.5px;
  font-weight: 750;
  line-height: 1.4;
  color: rgba(229,231,235,.88);
}
.facEtab--current .facEtab__body{ color: rgba(219,255,241,.98); font-weight: 850; }

/* ---- Séparateur "notre faculté" ---- */
.facDivider{
  margin: 36px 0 20px;
  display:flex; align-items:center; gap:14px;
  color: var(--muted);
  font-size: 12px; font-weight:800; text-transform:uppercase; letter-spacing:.08em;
}
.facDivider::before, .facDivider::after{
  content:""; flex:1; height:1px; background: rgba(255,255,255,.14);
}

/* ---- Hero secondaire (faculté) ---- */
.facHero2{
  border-radius: 26px;
  overflow:hidden;
  border: 1px solid rgba(255,255,255,.10);
  background: rgba(255,255,255,.05);
  box-shadow: var(--shadow);
  display:grid;
  grid-template-columns: 1.1fr .9fr;
}
.facHero2__left{padding: 28px;}
.facHero2__right{position:relative; min-height: 300px; background: rgba(0,0,0,.15);}
.facHero2 h2{ margin: 12px 0 10px; font-size: clamp(20px, 2.6vw, 30px); line-height:1.15; font-weight: 950; }
.facHero2 p{ margin:0; color: rgba(229,231,235,.84); line-height:1.7; font-size:14px; }

.facMiniList{ margin: 14px 0 0; padding:0; list-style:none; display:flex; flex-wrap:wrap; gap:8px; }
.facMiniList li{
  padding: 7px 11px;
  border-radius: 999px;
  border: 1px solid rgba(255,255,255,.14);
  background: rgba(255,255,255,.06);
  font-size: 12px; font-weight: 800;
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

.facSource{
  margin-top: 28px;
  font-size: 12px;
  color: rgba(229,231,235,.5);
  line-height: 1.7;
}
.facSource a{ color: rgba(167,243,208,.9); }

.reveal{
  opacity:0;
  transform: translateY(10px);
  transition: opacity 520ms ease, transform 520ms ease;
}
.reveal.is-in{opacity:1; transform: translateY(0);}

@media (max-width: 980px){
  .facHero, .facHero2{grid-template-columns: 1fr;}
  .facCard{grid-column: span 12;}
  .facStats{ grid-template-columns: repeat(2, minmax(0,1fr)); }
  .facEtabs{ grid-template-columns: 1fr; }
}
@media (max-width: 640px){
  .fac__container{padding: 18px 0 52px;}
  .facHero, .facHero2{border-radius: 18px;}
  .facHero__left, .facHero2__left{padding: 18px;}
}
</style>
@endsection

@section('scripts')
<script>
(() => {
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

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

  // ===== DIAPORAMA GÉNÉRIQUE (héro université) =====
  function initSlides(){
    document.querySelectorAll('[data-fac-show]').forEach((box) => {
      const slides = box.querySelectorAll('.fac-slide');
      if (slides.length < 2) return;

      const dots = box.parentElement?.querySelector('.fac-dots');
      let i = 0;
      let timer = null;
      const delay = parseInt(box.dataset.facShow || '4500', 10);

      const show = (n) => {
        slides[i].classList.remove('is-active');
        if (dots) dots.children[i]?.classList.remove('is-on');
        i = (n + slides.length) % slides.length;
        slides[i].classList.add('is-active');
        if (dots) dots.children[i]?.classList.add('is-on');
      };
      const start = () => { if (!reduce && !timer) timer = setInterval(() => show(i + 1), delay); };
      const stop = () => { clearInterval(timer); timer = null; };

      if (dots) {
        slides.forEach((_, n) => {
          const d = document.createElement('i');
          if (n === i) d.classList.add('is-on');
          d.addEventListener('click', () => { show(n); stop(); start(); });
          dots.appendChild(d);
        });
      }
      box.addEventListener('mouseenter', stop);
      box.addEventListener('mouseleave', start);
      start();
    });
  }

  // ===== QUOTE ROTATION =====
  const quote = document.querySelector('[data-fac-quote]');
  const quotes = [
    "Créée en 2003 pour ancrer l’enseignement supérieur dans le Nord-Ouest tunisien.",
    "13 établissements, 4 gouvernorats : Jendouba, Béja, Le Kef, Siliana.",
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
    initSlides();
    tickQuote();
    if (!reduce) setInterval(tickQuote, 3800);
  });
})();
</script>
@endsection

@section('content')
<main class="fac">
  <div class="fac__container">

    {{-- HERO UNIVERSITÉ --}}
    <section class="facHero reveal">
      <div class="facHero__left">
        <span class="facKicker">AEEJ • Notre Université</span>
        <h1>Université de Jendouba</h1>
        <p>
          Créée par le décret n°1662-2003 du 4 août 2003, l’Université de Jendouba a été fondée
          pour ancrer l’enseignement supérieur et la recherche dans le Nord-Ouest tunisien,
          au service de quatre gouvernorats : Jendouba, Béja, Le Kef et Siliana. Elle regroupe
          aujourd’hui une faculté, deux écoles et une dizaine d’instituts supérieurs, couvrant
          le droit, l’économie et la gestion, les sciences humaines, les langues, l’informatique,
          l’agriculture, la biotechnologie, la santé, le sport, la musique et le théâtre.
        </p>
        <div class="facHero__quote" data-fac-quote></div>
        <div class="facHero__actions">
          <a class="facBtn facBtn--primary" href="#notre-faculte">Voir notre faculté ↓</a>
          <a class="facBtn" href="https://www.uj.rnu.tn/fr" target="_blank" rel="noopener">Site officiel de l’université</a>
        </div>
      </div>

      <div class="facHero__right">
        @if(count($universiteImages ?? []) > 0)
          <div class="facHero__media" data-fac-show="5000">
            @foreach($universiteImages as $src)
              <img class="fac-slide @if($loop->first) is-active @endif" src="{{ $src }}" alt="Université de Jendouba" @if(!$loop->first) loading="lazy" @endif>
            @endforeach
          </div>
          @if(count($universiteImages) > 1)<span class="fac-dots"></span>@endif
          <div class="facHero__cap">Campus universitaire Mohamed Yaalaoui, Jendouba</div>
        @else
          <div class="facHero__soon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 21h18M4 21V9l8-5 8 5v12M9 21v-6h6v6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <span>Photos du campus à venir</span>
          </div>
        @endif
      </div>
    </section>

    {{-- CHIFFRES CLÉS --}}
    <section class="facStats reveal">
      <div class="facStat"><b>2003</b><span>Année de création</span></div>
      <div class="facStat"><b>13</b><span>Établissements</span></div>
      <div class="facStat"><b>≈ 15 000</b><span>Étudiants</span></div>
      <div class="facStat"><b>4</b><span>Gouvernorats couverts</span></div>
    </section>

    {{-- NOS ÉTABLISSEMENTS --}}
    <section class="facSection reveal">
      <div class="facSection__head">
        <h2>Nos établissements</h2>
        <p>Une faculté, deux écoles et une dizaine d’instituts supérieurs répartis entre Jendouba, Béja, Le Kef et Siliana.</p>
      </div>
      <div class="facEtabs">
        @foreach($etablissements as $e)
          <article class="facEtab reveal @if(!empty($e['current'])) facEtab--current @endif">
            @if(count($e['images']) > 0)
              <div class="facEtab__media" @if(count($e['images']) > 1) data-fac-show="3200" @endif>
                @foreach($e['images'] as $img)
                  <img class="fac-slide @if($loop->first) is-active @endif" src="{{ $img }}" alt="{{ $e['nom'] }}" loading="lazy">
                @endforeach
                @if(!empty($e['current']))<span class="facEtab__dot" title="Notre faculté"></span>@endif
              </div>
            @else
              <div class="facEtab__media facEtab__media--empty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </div>
            @endif
            <div class="facEtab__body">{{ $e['nom'] }}</div>
          </article>
        @endforeach
      </div>
    </section>

    {{-- SÉPARATEUR --}}
    <div class="facDivider reveal" id="notre-faculte">Notre faculté</div>

    {{-- HERO FACULTÉ --}}
    <section class="facHero2 reveal">
      <div class="facHero2__left">
        <span class="facKicker">FSJEG Jendouba</span>
        <h2>Faculté des Sciences Juridiques, Économiques et de Gestion</h2>
        <p>
          Créée par la loi n°93-75 du 12 juillet 1993, la FSJEG de Jendouba est la toute première
          faculté du Nord-Ouest tunisien — antérieure à l’université elle-même. C’est le cadre
          académique de la quasi-totalité des membres de l’AEEJ, organisé en six départements :
          droit public, droit privé, sciences de gestion, sciences économiques, informatique et
          méthodes quantitatives.
        </p>
        <ul class="facMiniList">
          <li>Licence en Droit</li>
          <li>Licence en Sciences économiques</li>
          <li>Licence en Sciences de gestion</li>
          <li>Licence en Informatique de gestion</li>
          <li>Mastères recherche &amp; professionnels</li>
        </ul>
      </div>

      <div class="facHero2__right">
        @if(count($faculteImages ?? []) > 0)
          <div class="facHero__media" data-fac-show="4200">
            @foreach($faculteImages as $src)
              <img class="fac-slide @if($loop->first) is-active @endif" src="{{ $src }}" alt="Faculté de Jendouba" @if(!$loop->first) loading="lazy" @endif>
            @endforeach
          </div>
          @if(count($faculteImages) > 1)<span class="fac-dots"></span>@endif
        @endif
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
          <img src="{{ asset('images/faculte/fac2.jpg') }}" alt="Études à la faculté">
          <div class="facMedia__cap">Apprendre, évoluer, réussir</div>
        </div>
      </article>

      <article class="facCard reveal">
        <div class="facMedia">
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

    <p class="facSource reveal">
      Sources : <a href="https://fr.wikipedia.org/wiki/Universit%C3%A9_de_Jendouba" target="_blank" rel="noopener">Université de Jendouba — Wikipédia</a>,
      <a href="https://www.uj.rnu.tn/fr" target="_blank" rel="noopener">uj.rnu.tn</a>,
      <a href="https://www.fsjegj.rnu.tn/fr" target="_blank" rel="noopener">fsjegj.rnu.tn</a>.
    </p>

  </div>
</main>
@endsection
