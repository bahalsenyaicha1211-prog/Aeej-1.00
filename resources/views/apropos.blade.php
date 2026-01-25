@extends('layouts.public')

@section('title', 'À propos - AEEJ')

@section('styles')
<style>
  :root{
    --g0:#052e2b;
    --g1:#0b3a33;
    --green:#22c55e;
    --green2:#16a34a;
    --text:#0f172a;
    --muted:#475569;
    --card:#ffffff;
    --bg:#f6fbf8;
    --border: rgba(2,6,23,.10);
    --shadow: 0 14px 40px rgba(2,6,23,.10);
    --r: 18px;
  }

  .about{
    background:
      radial-gradient(900px 520px at 8% 0%, rgba(34,197,94,.18), transparent 55%),
      radial-gradient(900px 520px at 100% 25%, rgba(22,163,74,.12), transparent 60%),
      linear-gradient(180deg, #ffffff, var(--bg));
    color: var(--text);
    padding: 48px 16px 70px;
  }

  .about__wrap{
    max-width: 1100px;
    margin: 0 auto;
  }

  /* HERO */
  .about__hero{
    display:grid;
    grid-template-columns: 1.2fr .8fr;
    gap: 18px;
    align-items: stretch;
    margin-bottom: 18px;
  }

  .about__heroCard{
    border:1px solid var(--border);
    border-radius: 24px;
    padding: 22px;
    background: rgba(255,255,255,.78);
    backdrop-filter: blur(10px);
    box-shadow: var(--shadow);
    overflow:hidden;
    position:relative;
  }

  .about__heroCard::before{
    content:"";
    position:absolute;
    inset:0;
    background:
      radial-gradient(520px 220px at 0% 0%, rgba(34,197,94,.22), transparent 65%),
      radial-gradient(520px 220px at 100% 100%, rgba(22,163,74,.16), transparent 65%);
    pointer-events:none;
  }

  .about__kicker{
    position:relative;
    display:inline-flex;
    align-items:center;
    gap:10px;
    padding: 8px 12px;
    border-radius: 999px;
    border:1px solid rgba(34,197,94,.25);
    background: rgba(34,197,94,.08);
    color: #0b3a33;
    font-weight: 800;
    font-size: 12px;
    letter-spacing: .08em;
    text-transform: uppercase;
  }

  .about__kickerDot{
    width:10px;height:10px;border-radius:999px;
    background: linear-gradient(135deg, var(--green), var(--green2));
    box-shadow: 0 0 0 4px rgba(34,197,94,.15);
  }

  .about__title{
    position:relative;
    margin: 14px 0 8px;
    font-size: clamp(26px, 3.2vw, 42px);
    line-height: 1.1;
    letter-spacing: -.02em;
    font-weight: 950;
  }

  .about__lead{
    position:relative;
    margin: 0;
    color: var(--muted);
    font-size: 15px;
    line-height: 1.7;
    max-width: 62ch;
  }

  .about__heroAside{
    border-radius: 24px;
    padding: 18px;
    background: linear-gradient(135deg, var(--g0), var(--g1));
    color: rgba(255,255,255,.92);
    box-shadow: var(--shadow);
    border:1px solid rgba(255,255,255,.10);
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    gap: 14px;
  }

  .about__asideTitle{
    margin:0;
    font-weight: 900;
    font-size: 16px;
    letter-spacing: .2px;
  }

  .about__asideText{
    margin: 6px 0 0;
    color: rgba(255,255,255,.80);
    line-height: 1.7;
    font-size: 13px;
  }

  .about__stats{
    display:grid;
    grid-template-columns: repeat(3, minmax(0,1fr));
    gap: 10px;
  }

  .about__stat{
    border-radius: 16px;
    border:1px solid rgba(255,255,255,.14);
    background: rgba(255,255,255,.06);
    padding: 12px;
  }
  .about__statN{
    font-size: 20px;
    font-weight: 950;
    line-height: 1.1;
  }
  .about__statL{
    margin-top: 4px;
    font-size: 12px;
    color: rgba(255,255,255,.78);
  }

  /* SECTIONS */
  .about__grid{
    display:grid;
    grid-template-columns: repeat(12, minmax(0,1fr));
    gap: 14px;
    margin-top: 14px;
  }

  .about__card{
    grid-column: span 6;
    border:1px solid var(--border);
    border-radius: var(--r);
    background: var(--card);
    box-shadow: 0 10px 28px rgba(2,6,23,.08);
    padding: 18px;
  }

  .about__h{
    margin: 0 0 8px;
    font-size: 16px;
    font-weight: 950;
    letter-spacing: .2px;
    display:flex;
    align-items:center;
    gap:10px;
  }

  .about__hIcon{
    width:38px;height:38px;border-radius: 14px;
    display:grid;place-items:center;
    background: rgba(34,197,94,.10);
    border:1px solid rgba(34,197,94,.22);
    color: #0b3a33;
    font-weight: 950;
  }

  .about__p{
    margin: 0;
    color: var(--muted);
    line-height: 1.75;
    font-size: 14px;
  }

  /* Timeline */
  .timeline{
    margin-top: 10px;
    display:flex;
    flex-direction:column;
    gap: 12px;
  }
  .titem{
    display:flex;
    gap: 12px;
    align-items:flex-start;
    padding: 12px;
    border-radius: 16px;
    border:1px solid rgba(2,6,23,.08);
    background: rgba(34,197,94,.04);
  }
  .tbadge{
    flex: 0 0 auto;
    font-weight: 900;
    font-size: 12px;
    color: #0b3a33;
    border-radius: 999px;
    padding: 8px 10px;
    background: rgba(34,197,94,.12);
    border:1px solid rgba(34,197,94,.25);
  }
  .tcontent{min-width:0}
  .ttitle{margin:0; font-weight: 900; font-size: 13px}
  .ttext{margin: 4px 0 0; color: var(--muted); line-height: 1.6; font-size: 13px}

  /* List chips */
  .chips{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-top: 10px;
  }
  .chip{
    padding: 10px 12px;
    border-radius: 999px;
    border:1px solid rgba(2,6,23,.10);
    background: rgba(2,6,23,.02);
    color: #0f172a;
    font-weight: 700;
    font-size: 12px;
  }
  .chip strong{ color: #0b3a33; }

  /* Footer CTA */
  .about__cta{
    margin-top: 14px;
    border-radius: 22px;
    border:1px solid rgba(34,197,94,.25);
    background: linear-gradient(135deg, rgba(34,197,94,.10), rgba(22,163,74,.06));
    padding: 18px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap: 14px;
  }
  .about__cta h3{
    margin:0;
    font-weight: 950;
    font-size: 16px;
  }
  .about__cta p{
    margin:6px 0 0;
    color: var(--muted);
    line-height: 1.6;
    font-size: 13px;
  }
  .about__btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding: 12px 14px;
    border-radius: 14px;
    border:1px solid rgba(34,197,94,.28);
    background: linear-gradient(135deg, rgba(34,197,94,.22), rgba(22,163,74,.14));
    color: #0b3a33;
    font-weight: 900;
    text-decoration:none;
    white-space:nowrap;
  }
  .about__btn:hover{ filter: brightness(1.03); }

  /* Responsive */
  @media (max-width: 980px){
    .about__hero{ grid-template-columns: 1fr; }
    .about__card{ grid-column: span 12; }
    .about__stats{ grid-template-columns: repeat(3, minmax(0,1fr)); }
  }
  @media (max-width: 560px){
    .about{ padding: 34px 14px 60px; }
    .about__heroCard{ padding: 18px; }
    .about__stats{ grid-template-columns: 1fr; }
    .about__cta{ flex-direction:column; align-items:stretch; }
  }
</style>
@endsection

@section('content')
<main class="about">
  <div class="about__wrap">

    {{-- HERO --}}
    <section class="about__hero" aria-label="Présentation de l’AEEJ">
      <div class="about__heroCard">
        <div class="about__kicker">
          <span class="about__kickerDot"></span>
          Association des Étudiants Étrangers à Jendouba
        </div>

        <h1 class="about__title">Une famille née du destin, unie par le savoir.</h1>

        <p class="about__lead">
          En janvier 2005, des étudiants venus de différents horizons se sont retrouvés à Jendouba,
          au nord-ouest de la Tunisie. Ils partageaient le même objectif : la quête du savoir.
          De rencontres en amitiés, ils ont choisi de devenir une famille : l’AEEJ est née.
        </p>
      </div>

      <aside class="about__heroAside" aria-label="Chiffres clés">
        <div>
          <h2 class="about__asideTitle">Aujourd’hui, 21 ans plus tard</h2>
          <p class="about__asideText">
            L’AEEJ regroupe des étudiants inscrits à la Faculté des Sciences Juridiques,
            Économiques et de Gestion de Jendouba, organisés en communautés.
          </p>
        </div>

        <div class="about__stats">
          <div class="about__stat">
            <div class="about__statN">2005</div>
            <div class="about__statL">Année de création</div>
          </div>
          <div class="about__stat">
            <div class="about__statN">21+</div>
            <div class="about__statL">Ans d’existence</div>
          </div>
          <div class="about__stat">
            <div class="about__statN">7</div>
            <div class="about__statL">Communautés</div>
          </div>
        </div>
      </aside>
    </section>

    {{-- HISTOIRE / TIMELINE --}}
    <section class="about__grid" aria-label="Histoire et fonctionnement">
      <div class="about__card">
        <h2 class="about__h">
          <span class="about__hIcon">⟡</span>
          Notre histoire
        </h2>
        <p class="about__p">
          Un groupe d’étudiants, réunis par leurs études à Jendouba, a construit une solidarité
          réelle : accueil, entraide, partage culturel et accompagnement. C’est cette vision
          qui a fait naître l’Association des Étudiants Étrangers à Jendouba.
        </p>

        <div class="timeline">
          <div class="titem">
            <div class="tbadge">Jan 2005</div>
            <div class="tcontent">
              <p class="ttitle">Naissance de l’AEEJ</p>
              <p class="ttext">Les étudiants se donnent un “nom de famille” commun et fondent l’association.</p>
            </div>
          </div>

          <div class="titem">
            <div class="tbadge">Chaque année</div>
            <div class="tcontent">
              <p class="ttitle">Renouvellement & engagement</p>
              <p class="ttext">La vie associative continue grâce à l’implication des membres et du bureau exécutif.</p>
            </div>
          </div>

          <div class="titem">
            <div class="tbadge">Aujourd’hui</div>
            <div class="tcontent">
              <p class="ttitle">Une famille solide</p>
              <p class="ttext">Plusieurs communautés représentent la diversité des étudiants à Jendouba.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="about__card">
        <h2 class="about__h">
          <span class="about__hIcon">✓</span>
          Gouvernance
        </h2>
        <p class="about__p">
          L’association est présidée par un président élu et un bureau exécutif.
          Le mandat est d’une année, renouvelable une fois, afin d’assurer la continuité
          et permettre l’alternance.
        </p>

        <div class="chips" aria-label="Principes de gouvernance">
          <div class="chip"><strong>Élection</strong> annuelle</div>
          <div class="chip"><strong>Mandat</strong> 1 an</div>
          <div class="chip"><strong>Renouvelable</strong> 1 fois</div>
          <div class="chip"><strong>Bureau</strong> exécutif</div>
        </div>

        <div style="margin-top:14px;">
          <h2 class="about__h" style="margin-top:0;">
            <span class="about__hIcon">¤</span>
            Financement
          </h2>
          <p class="about__p">
            Financièrement indépendante, l’AEEJ met en place une cotisation annuelle destinée
            à couvrir les activités traditionnelles et le fonctionnement courant.
          </p>
        </div>
      </div>

      <div class="about__card">
        <h2 class="about__h">
          <span class="about__hIcon">★</span>
          Activités traditionnelles
        </h2>
        <p class="about__p">
          Nos activités renforcent l’intégration, la cohésion et le vivre-ensemble :
        </p>

        <div class="timeline">
          <div class="titem">
            <div class="tbadge">Accueil</div>
            <div class="tcontent">
              <p class="ttitle">Fête d’intégration des nouveaux</p>
              <p class="ttext">Un moment pour accueillir, orienter et intégrer les nouveaux étudiants.</p>
            </div>
          </div>
          <div class="titem">
            <div class="tbadge">Sport</div>
            <div class="tcontent">
              <p class="ttitle">Mini CAN entre communautés</p>
              <p class="ttext">Compétition conviviale qui renforce l’unité dans la diversité.</p>
            </div>
          </div>
          <div class="titem">
            <div class="tbadge">Culture</div>
            <div class="tcontent">
              <p class="ttitle">Journée culturelle</p>
              <p class="ttext">Partage des cultures, expositions, échanges et célébration des identités.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="about__card">
        <h2 class="about__h">
          <span class="about__hIcon">⇄</span>
          Partenariats & impact
        </h2>
        <p class="about__p">
          Nous collaborons avec des organismes et des personnalités pour mener des actions
          éducatives (formations) et événementielles. L’objectif : améliorer l’expérience
          étudiante et développer des compétences utiles (soft skills, employabilité, leadership).
        </p>

        <div class="chips" aria-label="Types d’actions">
          <div class="chip"><strong>Formations</strong> & ateliers</div>
          <div class="chip"><strong>Événements</strong> communautaires</div>
          <div class="chip"><strong>Encadrement</strong> & orientation</div>
        </div>
      </div>
    </section>

    {{-- CTA --}}
    <section class="about__cta" aria-label="Appel à l’action">
      <div>
        <h3>Découvrir nos activités et revivre nos événements</h3>
        <p>
          Consultez la galerie et les annonces pour suivre la vie de l’AEEJ au quotidien.
        </p>
      </div>
      <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a class="about__btn" href="{{ route('galerie') }}">Voir la galerie</a>
        <a class="about__btn" href="{{ route('accueil') }}">Retour à l’accueil</a>
      </div>
    </section>

  </div>
</main>
@endsection
