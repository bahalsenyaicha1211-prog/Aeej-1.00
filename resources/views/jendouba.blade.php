@extends('layouts.public')

@section('title', 'Découvrir Jendouba - AEEJ')

@section('styles')
<style>
/* ========== PAGE DÉCOUVERTE JENDOUBA (scoped .jdb) ========== */
.jdb{
  --green:#22c55e;
  --green2:#16a34a;
  --g0:#052e2b;
  --g1:#0b3a33;
  --text:#0f172a;
  --muted:#475569;
  --card:#ffffff;
  --bg:#f6fbf8;
  --border: rgba(2,6,23,.10);
  --shadow: 0 14px 40px rgba(2,6,23,.10);
  --r:18px;
  color: var(--text);
  background:
    radial-gradient(900px 520px at 10% -8%, rgba(34,197,94,.16), transparent 55%),
    radial-gradient(900px 520px at 105% 12%, rgba(22,163,74,.12), transparent 60%),
    linear-gradient(180deg, #ffffff, var(--bg));
}
.jdb *{ box-sizing:border-box; }
.jdb__wrap{ width:min(1140px, 92vw); margin:0 auto; padding: 108px 0 72px; }

/* HERO */
.jdbHero{
  position:relative; border-radius:26px; overflow:hidden;
  min-height: 460px; box-shadow: var(--shadow);
  border:1px solid var(--border);
}
.jdbHero__media{ position:absolute; inset:0; background:#0b1220; }
.jdbHero__img{
  position:absolute; inset:0; width:100%; height:100%; object-fit:cover;
  opacity:0; transition: opacity 1s ease;
}
.jdbHero__img.is-active{ opacity:1; }
.jdbHero__overlay{
  position:absolute; inset:0;
  background: linear-gradient(90deg, rgba(4,20,15,.82), rgba(4,20,15,.30)),
              linear-gradient(180deg, rgba(4,20,15,.10), rgba(4,20,15,.65));
}
.jdbHero__content{ position:relative; padding: clamp(24px, 4vw, 44px); max-width: 720px; color:#fff; }
.jdbKicker{
  display:inline-flex; align-items:center; gap:10px;
  padding: 8px 13px; border-radius:999px;
  background: rgba(34,197,94,.18); border:1px solid rgba(74,222,128,.5);
  color:#eafff1; font-weight:800; font-size:12px; letter-spacing:.06em; text-transform:uppercase;
}
.jdbKicker__dot{ width:9px; height:9px; border-radius:999px; background:#4ade80; box-shadow:0 0 0 4px rgba(74,222,128,.25); }
.jdbHero__content h1{
  margin: 16px 0 10px; font-weight:950; line-height:1.08;
  font-size: clamp(28px, 4vw, 46px); letter-spacing:-.02em;
  text-shadow: 0 2px 18px rgba(0,0,0,.35);
}
.jdbHero__content p{ margin:0; color: rgba(255,255,255,.9); line-height:1.7; font-size: 15.5px; max-width: 60ch; }
.jdbHero__text{ margin-top:14px; font-weight:800; color:#a7f3d0; min-height:1.4em; transition: opacity .24s ease, transform .24s ease; }
.jdbHero__actions{ margin-top:22px; display:flex; gap:12px; flex-wrap:wrap; }
.jdbBtn{
  display:inline-flex; align-items:center; gap:8px;
  padding: 11px 18px; border-radius:12px; font-weight:800; font-size:14px;
  border:1px solid rgba(255,255,255,.4); color:#fff; background: rgba(255,255,255,.08);
  transition: transform .12s ease, background .12s ease;
}
.jdbBtn:hover{ transform: translateY(-2px); background: rgba(255,255,255,.16); }
.jdbBtn--primary{ background: linear-gradient(135deg, var(--green), var(--green2)); border-color: transparent; color:#052e2b; }

/* BARRE DE STATS */
.jdbStats{
  margin-top: 18px;
  display:grid; grid-template-columns: repeat(4, 1fr); gap:12px;
}
.jdbStat{
  background: var(--card); border:1px solid var(--border); border-radius:16px;
  padding:16px; box-shadow: 0 10px 26px rgba(2,6,23,.06);
}
.jdbStat__v{ font-size: clamp(20px, 2.4vw, 28px); font-weight:950; line-height:1.1; }
.jdbStat__l{ margin-top:4px; font-size:12.5px; color: var(--muted); }

/* SECTIONS */
.jdbSection{ margin-top: 46px; }
.jdbSection__head{ display:flex; align-items:baseline; gap:12px; margin-bottom:16px; }
.jdbSection__eyebrow{
  font-size:12px; font-weight:900; letter-spacing:.12em; text-transform:uppercase;
  color: var(--green2);
}
.jdbSection h2{ margin:0; font-size: clamp(22px, 2.6vw, 30px); font-weight:950; letter-spacing:-.01em; }
.jdbLead{ margin:0 0 18px; color: var(--muted); line-height:1.75; font-size:15px; max-width: 74ch; }

.jdbGrid{ display:grid; grid-template-columns: 1.05fr .95fr; gap:18px; align-items:stretch; }
.jdbGrid--rev .jdbCard--media{ order:-1; }

.jdbCard{
  background: var(--card); border:1px solid var(--border); border-radius: var(--r);
  box-shadow: 0 10px 28px rgba(2,6,23,.07); padding: 20px;
}
.jdbCard h3{ margin:0 0 8px; font-size:16px; font-weight:900; }
.jdbCard p{ margin:0 0 10px; color: var(--muted); line-height:1.75; font-size:14px; }
.jdbCard--media{ padding:0; overflow:hidden; }
.jdbCard--media img{ width:100%; height:100%; min-height: 240px; object-fit:cover; display:block; }

.jdbList{ list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:9px; }
.jdbList li{ position:relative; padding-left: 22px; color: var(--muted); font-size:14px; line-height:1.6; }
.jdbList li::before{
  content:""; position:absolute; left:0; top:.5em; width:9px; height:9px; border-radius:3px;
  background: linear-gradient(135deg, var(--green), var(--green2));
}

/* Chips faits */
.jdbFacts{ display:flex; flex-wrap:wrap; gap:10px; }
.jdbFact{
  display:flex; flex-direction:column; gap:2px;
  padding: 10px 14px; border-radius:14px;
  background: rgba(34,197,94,.06); border:1px solid rgba(34,197,94,.18);
}
.jdbFact b{ font-size:13.5px; color: var(--g1); }
.jdbFact span{ font-size:11.5px; color: var(--muted); }

/* Climat */
.jdbClimate{ display:grid; grid-template-columns: repeat(4, 1fr); gap:12px; margin-top:6px; }
.jdbClimate__c{
  background: var(--card); border:1px solid var(--border); border-radius:16px; padding:16px;
  box-shadow: 0 10px 26px rgba(2,6,23,.06);
}
.jdbClimate__c b{ display:block; font-size:12px; letter-spacing:.06em; text-transform:uppercase; color: var(--green2); }
.jdbClimate__c .n{ font-size:20px; font-weight:950; margin:6px 0 2px; }
.jdbClimate__c small{ color: var(--muted); font-size:12px; }

/* Coût de la vie */
.jdbPrices{ display:grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap:12px; margin-top:8px; }
.jdbPrice{ background: var(--card); border:1px solid var(--border); border-radius:14px; padding:14px 16px; box-shadow:0 8px 22px rgba(2,6,23,.05); }
.jdbPrice b{ font-size:15px; }
.jdbPrice span{ display:block; font-size:12.5px; color: var(--muted); margin-top:2px; }

/* Visiter : cartes */
.jdbPlaces{ display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:16px; }
.jdbPlace{
  background: var(--card); border:1px solid var(--border); border-radius:18px; overflow:hidden;
  box-shadow: 0 10px 28px rgba(2,6,23,.07);
  display:flex; flex-direction:column;
  transition: transform .16s ease, box-shadow .16s ease;
}
.jdbPlace:hover{ transform: translateY(-4px); box-shadow: 0 22px 46px rgba(2,6,23,.14); }
.jdbPlace__strip{ height:8px; background: linear-gradient(90deg, var(--green), var(--green2)); }
.jdbPlace__b{ padding:18px; flex:1; display:flex; flex-direction:column; }
.jdbPlace__tag{ font-size:11px; font-weight:900; letter-spacing:.06em; text-transform:uppercase; color: var(--green2); }
.jdbPlace h3{ margin:6px 0 8px; font-size:17px; font-weight:950; }
.jdbPlace p{ margin:0; font-size:13.5px; color: var(--muted); line-height:1.65; }
.jdbPlace__meta{ margin-top:auto; padding-top:12px; font-size:12px; color: var(--muted); font-weight:800; }

/* Bon à savoir */
.jdbTips{ display:grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap:14px; }
.jdbTip{ background: rgba(34,197,94,.05); border:1px solid rgba(34,197,94,.18); border-radius:16px; padding:16px; }
.jdbTip b{ display:block; margin-bottom:4px; font-size:14px; color: var(--g1); }
.jdbTip p{ margin:0; font-size:13px; color: var(--muted); line-height:1.65; }

.jdbNote{
  margin-top: 40px; padding:14px 16px; border-radius:14px;
  background: rgba(2,6,23,.03); border:1px solid var(--border);
  font-size:12px; color: var(--muted); line-height:1.6;
}

/* Reveal */
.reveal{ opacity:0; transform: translateY(16px); transition: opacity .6s ease, transform .6s ease; }
.reveal.is-in{ opacity:1; transform:none; }
@media (prefers-reduced-motion: reduce){ .reveal{ opacity:1; transform:none; } }

@media (max-width: 900px){
  .jdbGrid{ grid-template-columns: 1fr; }
  .jdbGrid--rev .jdbCard--media{ order:0; }
  .jdbStats{ grid-template-columns: repeat(2, 1fr); }
  .jdbClimate{ grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 560px){
  .jdb__wrap{ padding-top: 92px; }
  .jdbHero{ min-height: 400px; }
}
</style>
@endsection

@section('scripts')
<script>
(() => {
  const slides = document.querySelectorAll('[data-jdb-slide]');
  let s = 0;
  if (slides.length){ slides[0].classList.add('is-active'); }
  if (slides.length > 1){
    setInterval(() => {
      slides[s].classList.remove('is-active');
      s = (s + 1) % slides.length;
      slides[s].classList.add('is-active');
    }, 5000);
  }

  const heroText = document.querySelector('[data-jdb-text]');
  const phrases = [
    "Au pied des monts de la Kroumirie.",
    "Sur la plaine du Medjerda, grenier de la Tunisie.",
    "À deux pas de Bulla Regia et de Chemtou."
  ];
  let t = 0;
  function rotate(){
    if (!heroText) return;
    heroText.style.opacity = "0";
    heroText.style.transform = "translateY(6px)";
    setTimeout(() => {
      heroText.textContent = phrases[t];
      heroText.style.opacity = "1";
      heroText.style.transform = "translateY(0)";
      t = (t + 1) % phrases.length;
    }, 240);
  }
  if (heroText){ rotate(); setInterval(rotate, 4200); }

  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting){ e.target.classList.add('is-in'); obs.unobserve(e.target); } });
  }, { threshold: 0.15 });
  document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
})();
</script>
@endsection

@section('content')
<main class="jdb">
  <div class="jdb__wrap">

    {{-- HERO --}}
    <section class="jdbHero reveal">
      <div class="jdbHero__media">
        <img data-jdb-slide class="jdbHero__img" src="{{ asset('images/jendouba/p3.jpg') }}" alt="Paysage de Jendouba">
        <img data-jdb-slide class="jdbHero__img" src="{{ asset('images/jendouba/p1.jpg') }}" alt="Nature autour de Jendouba">
        <img data-jdb-slide class="jdbHero__img" src="{{ asset('images/jendouba/P4.jpg') }}" alt="Région de Jendouba">
        <div class="jdbHero__overlay"></div>
      </div>
      <div class="jdbHero__content">
        <span class="jdbKicker"><span class="jdbKicker__dot"></span> Découvrir · Nord-Ouest tunisien</span>
        <h1>Jendouba, entre plaine fertile et montagnes vertes</h1>
        <p>
          Chef-lieu du gouvernorat du même nom, Jendouba est une ville d'environ 47 500 habitants
          posée sur la plaine du Medjerda, au pied de la Kroumirie. Une des régions les plus
          agricoles et les plus boisées de Tunisie — et le foyer de l'AEEJ.
        </p>
        <div class="jdbHero__text" data-jdb-text></div>
        <div class="jdbHero__actions">
          <a class="jdbBtn jdbBtn--primary" href="#nature">Nature &amp; climat</a>
          <a class="jdbBtn" href="#visiter">Que visiter ?</a>
        </div>
      </div>
    </section>

    {{-- STATS --}}
    <section class="jdbStats reveal" aria-label="Repères">
      <div class="jdbStat"><div class="jdbStat__v">~47 500</div><div class="jdbStat__l">habitants (ville, 2022)</div></div>
      <div class="jdbStat"><div class="jdbStat__v">~416 000</div><div class="jdbStat__l">habitants (gouvernorat)</div></div>
      <div class="jdbStat"><div class="jdbStat__v">143 m</div><div class="jdbStat__l">altitude</div></div>
      <div class="jdbStat"><div class="jdbStat__v">2003</div><div class="jdbStat__l">création de l'Université</div></div>
    </section>

    {{-- EN BREF --}}
    <section class="jdbSection reveal">
      <div class="jdbSection__head">
        <span class="jdbSection__eyebrow">Situation</span>
        <h2>En bref</h2>
      </div>
      <p class="jdbLead">
        Jendouba se trouve au nord-ouest de la Tunisie, à une cinquantaine de kilomètres de la
        frontière algérienne et de la Méditerranée. C'est un carrefour routier vers Le Kef, Béja,
        Tabarka, Aïn Draham et Ghardimaou, sur la ligne de train Tunis&nbsp;–&nbsp;Ghardimaou.
        La ville s'est longtemps appelée <b>Souk El Arba</b> (« le marché du mercredi ») jusqu'en 1966.
      </p>
      <div class="jdbFacts">
        <div class="jdbFact"><b>Région</b><span>Nord-Ouest, Tell atlasique</span></div>
        <div class="jdbFact"><b>Fleuve</b><span>Medjerda (le plus long du pays)</span></div>
        <div class="jdbFact"><b>Relief</b><span>Plaine agricole + monts de la Kroumirie</span></div>
        <div class="jdbFact"><b>Ancien nom</b><span>Souk El Arba (jusqu'en 1966)</span></div>
        <div class="jdbFact"><b>Accès</b><span>Route &amp; rail depuis Tunis (~3 h)</span></div>
        <div class="jdbFact"><b>Antiquité</b><span>Colonia Libertina (romaine)</span></div>
      </div>
    </section>

    {{-- NATURE & CLIMAT --}}
    <section id="nature" class="jdbSection reveal">
      <div class="jdbSection__head">
        <span class="jdbSection__eyebrow">Nature</span>
        <h2>Végétation, forêts &amp; climat</h2>
      </div>
      <p class="jdbLead">
        Le gouvernorat compte près de <b>118 000 hectares de forêts</b> pour environ 286 000 hectares
        de terres agricoles. Au nord, la <b>Kroumirie</b> est couverte de vastes <b>forêts de
        chêne-liège</b> et compte parmi les zones les plus arrosées d'Afrique du Nord
        (1 000 à 1 500 mm de pluie par an à Aïn Draham, où il neige en hiver).
        La ville de Jendouba, elle, est plus sèche et plus chaude&nbsp;: climat semi-aride,
        hivers doux et pluvieux, étés torrides.
      </p>

      <div class="jdbClimate">
        <div class="jdbClimate__c"><b>Hiver (janv.)</b><div class="n">5° / 16°C</div><small>nuits fraîches, gel possible</small></div>
        <div class="jdbClimate__c"><b>Été (juil.–août)</b><div class="n">20° / 37°C</div><small>coups de chaleur &gt; 45°C</small></div>
        <div class="jdbClimate__c"><b>Pluie</b><div class="n">~500 mm/an</div><small>surtout nov. → mars ; été très sec</small></div>
        <div class="jdbClimate__c"><b>Record de chaleur</b><div class="n">49,0°C</div><small>août 2021 — record national tunisien</small></div>
      </div>

      <div class="jdbGrid" style="margin-top:18px;">
        <div class="jdbCard">
          <h3>Autour de la ville</h3>
          <ul class="jdbList">
            <li><b>Aïn Draham</b> (~30 km, 800 m d'altitude) : station de montagne, toits de tuiles rouges, air frais — surnommée « la petite Suisse tunisienne ».</li>
            <li><b>Parc national d'El Feïja</b> (près de Ghardimaou, 2 765 ha) : chêne-liège, sources, cerf de Berbérie réintroduit, sangliers.</li>
            <li><b>Barrages</b> de Beni M'tir, Bou Heurtma, Sidi El Barrak : lacs de montagne et retenues d'irrigation.</li>
          </ul>
          <p style="margin-top:12px; font-weight:700; color:var(--g1);">Meilleure période pour découvrir la région : le printemps (avril–mai) et l'automne (octobre–novembre), 15–20°C.</p>
        </div>
        <div class="jdbCard jdbCard--media">
          <img loading="lazy" src="{{ asset('images/jendouba/v2.jpg') }}" alt="Paysage du Nord-Ouest tunisien">
        </div>
      </div>
    </section>

    {{-- VIE ÉTUDIANTE & COÛT DE LA VIE --}}
    <section class="jdbSection reveal">
      <div class="jdbSection__head">
        <span class="jdbSection__eyebrow">Vivre &amp; étudier</span>
        <h2>Vie étudiante &amp; coût de la vie</h2>
      </div>
      <div class="jdbGrid jdbGrid--rev">
        <div class="jdbCard jdbCard--media">
          <img loading="lazy" src="{{ asset('images/jendouba/v1.jpg') }}" alt="Vie locale à Jendouba">
        </div>
        <div class="jdbCard">
          <h3>Une ville universitaire à taille humaine</h3>
          <p>
            L'<b>Université de Jendouba</b>, créée en 2003, accueille environ <b>15 000 étudiants</b>
            et fédère plusieurs établissements du Nord-Ouest (sciences, informatique, agronomie,
            droit, gestion, lettres, sport). La ville est calme, peu chère, et compte une importante
            communauté d'étudiants étrangers — dont les membres de l'AEEJ.
          </p>
          <ul class="jdbList">
            <li>Foyers universitaires et <b>restaurant universitaire</b> fortement subventionné.</li>
            <li>Logement privé (chambre ou petit studio) nettement moins cher qu'à Tunis ou sur la côte.</li>
            <li>Marché hebdomadaire, commerces de proximité, transport par « louage » vers les villes voisines.</li>
          </ul>
        </div>
      </div>

      <div class="jdbPrices">
        <div class="jdbPrice"><b>Chambre étudiante</b><span>~100 – 250 TND / mois (privé)</span></div>
        <div class="jdbPrice"><b>Petit studio</b><span>~250 – 450 TND / mois</span></div>
        <div class="jdbPrice"><b>Repas resto universitaire</b><span>quelques centaines de millimes à ~3 TND</span></div>
        <div class="jdbPrice"><b>Repas « populaire »</b><span>~7 – 15 TND</span></div>
        <div class="jdbPrice"><b>Trajet en louage (ville voisine)</b><span>~2 – 6 TND</span></div>
      </div>
      <p style="font-size:12px; color:var(--muted); margin-top:10px;">Prix indicatifs (2026), très variables selon la saison et le quartier — Jendouba reste l'une des villes les moins chères de Tunisie.</p>
    </section>

    {{-- À VISITER --}}
    <section id="visiter" class="jdbSection reveal">
      <div class="jdbSection__head">
        <span class="jdbSection__eyebrow">Découverte</span>
        <h2>À voir autour de Jendouba</h2>
      </div>
      <p class="jdbLead">
        La région concentre certains des plus beaux sites antiques et naturels du pays,
        accessibles à la journée depuis la ville.
      </p>

      <div class="jdbPlaces">
        <article class="jdbPlace">
          <div class="jdbPlace__strip"></div>
          <div class="jdbPlace__b">
            <span class="jdbPlace__tag">Site romain</span>
            <h3>Bulla Regia</h3>
            <p>Unique au monde : les riches Romains y bâtissaient leurs villas <b>sous terre</b> pour fuir la chaleur — cours à colonnades et mosaïques enterrées (maisons de la Chasse, de la Pêche, d'Amphitrite).</p>
            <div class="jdbPlace__meta">≈ 8 km · nord-est</div>
          </div>
        </article>

        <article class="jdbPlace">
          <div class="jdbPlace__strip"></div>
          <div class="jdbPlace__b">
            <span class="jdbPlace__tag">Carrières antiques</span>
            <h3>Chemtou (Simitthus)</h3>
            <p>Les carrières du célèbre <b>marbre jaune numidique</b> (<i>giallo antico</i>), le plus prestigieux de l'Empire romain. Site archéologique et musée sur la Medjerda.</p>
            <div class="jdbPlace__meta">≈ 20 km · ouest</div>
          </div>
        </article>

        <article class="jdbPlace">
          <div class="jdbPlace__strip"></div>
          <div class="jdbPlace__b">
            <span class="jdbPlace__tag">Montagne &amp; forêt</span>
            <h3>Aïn Draham &amp; la Kroumirie</h3>
            <p>Forêts de chêne-liège, sentiers de randonnée, panoramas, fraîcheur en été et neige en hiver. La station de montagne la plus connue de Tunisie.</p>
            <div class="jdbPlace__meta">≈ 30 km · nord</div>
          </div>
        </article>

        <article class="jdbPlace">
          <div class="jdbPlace__strip"></div>
          <div class="jdbPlace__b">
            <span class="jdbPlace__tag">Nature protégée</span>
            <h3>Parc national d'El Feïja</h3>
            <p>2 765 ha de forêt de montagne près de Ghardimaou : biodiversité, sources, et le <b>cerf de Berbérie</b>, réintroduit dans la région.</p>
            <div class="jdbPlace__meta">≈ 50 km · nord-ouest</div>
          </div>
        </article>

        <article class="jdbPlace">
          <div class="jdbPlace__strip"></div>
          <div class="jdbPlace__b">
            <span class="jdbPlace__tag">Mer</span>
            <h3>Tabarka</h3>
            <p>Station balnéaire du Nord-Ouest : les <b>Aiguilles</b>, le fort génois, les fonds coralliens et le festival de jazz en été.</p>
            <div class="jdbPlace__meta">≈ 60 km · nord</div>
          </div>
        </article>

        <article class="jdbPlace">
          <div class="jdbPlace__strip"></div>
          <div class="jdbPlace__b">
            <span class="jdbPlace__tag">Patrimoine mondial</span>
            <h3>Dougga</h3>
            <p>La cité romaine la mieux conservée d'Afrique du Nord, classée à l'UNESCO — capitole, théâtre, temples — à combiner avec la région du Kef.</p>
            <div class="jdbPlace__meta">≈ 90 km · sud-est</div>
          </div>
        </article>
      </div>
    </section>

    {{-- BON À SAVOIR --}}
    <section class="jdbSection reveal">
      <div class="jdbSection__head">
        <span class="jdbSection__eyebrow">Pratique</span>
        <h2>Bon à savoir</h2>
      </div>
      <div class="jdbTips">
        <div class="jdbTip"><b>Se déplacer</b><p>Train depuis Tunis (~3 h) ou « louage » (taxi collectif). Sur place, la ville se fait à pied ; louages et bus pour Aïn Draham, Tabarka, Le Kef.</p></div>
        <div class="jdbTip"><b>Quand venir</b><p>Avril–mai et octobre–novembre pour la douceur. L'hiver est vert mais humide et frais ; l'été peut être brûlant en plaine.</p></div>
        <div class="jdbTip"><b>Ambiance</b><p>Ville tranquille, accueillante, peu touristique. Rythme calme, hospitalité marquée, forte présence étudiante.</p></div>
        <div class="jdbTip"><b>Langues</b><p>Arabe et français au quotidien ; l'anglais progresse à l'université.</p></div>
      </div>
    </section>

    <p class="jdbNote">
      Informations rassemblées à partir de sources publiques (Wikipédia, Britannica, données climatiques —
      climate-data.org / climatestotravel.com) et données de terrain. Chiffres de population et de prix
      donnés à titre indicatif.
    </p>

  </div>
</main>
@endsection
