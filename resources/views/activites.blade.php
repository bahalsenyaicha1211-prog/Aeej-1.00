@extends('layouts.public')

@section('title', 'Activités - AEEJ')

@section('content')
<style>
/* =========================
   ACTIVITÉS (scoped)
   ========================= */
.act{
  --bg:#050812;
  --panel: rgba(255,255,255,.06);
  --border: rgba(255,255,255,.12);
  --txt:#e5e7eb;
  --muted: rgba(229,231,235,.72);
  --green:#34d399;
  --green2:#10b981;
  --shadow: 0 18px 45px rgba(0,0,0,.40);
  --r:18px;
  min-height: 100vh;
  color: var(--txt);
  background:
    radial-gradient(900px 520px at 15% -10%, rgba(16,185,129,.20), transparent 60%),
    radial-gradient(900px 520px at 110% 20%, rgba(52,211,153,.12), transparent 55%),
    linear-gradient(180deg, #050812, #070b14);
}

.act *{box-sizing:border-box}
.act__container{
  width: min(1120px, 92vw);
  margin: 0 auto;
  padding: 38px 0 70px;
}

.actHero{
  border: 1px solid rgba(255,255,255,.10);
  background: rgba(255,255,255,.05);
  border-radius: 24px;
  box-shadow: var(--shadow);
  overflow:hidden;
  padding: 22px;
  display:flex;
  align-items:flex-start;
  justify-content: space-between;
  gap: 12px;
  flex-wrap: wrap;
}
.actHero__left{max-width: 720px;}
.actKicker{
  display:inline-flex; align-items:center; gap:10px;
  padding: 8px 12px;
  border-radius: 999px;
  background: rgba(16,185,129,.14);
  border: 1px solid rgba(16,185,129,.25);
  color: rgba(167,243,208,.95);
  font-weight: 800;
  font-size: 13px;
}
.actHero h1{
  margin: 12px 0 8px;
  font-size: clamp(22px, 2.8vw, 38px);
  font-weight: 950;
  line-height: 1.1;
}
.actHero p{
  margin: 0;
  color: rgba(229,231,235,.84);
  line-height: 1.7;
  font-size: 14px;
}
.actHero__right{
  display:flex; gap:10px; flex-wrap:wrap; align-items:center;
}
.actBtn{
  display:inline-flex; align-items:center; justify-content:center;
  padding: 11px 14px;
  border-radius: 14px;
  border: 1px solid rgba(255,255,255,.14);
  background: rgba(255,255,255,.06);
  color: var(--txt);
  font-weight: 900;
  cursor:pointer;
  transition: transform .12s ease, background .12s ease, border-color .12s ease;
  text-decoration:none;
}
.actBtn:hover{
  transform: translateY(-1px);
  background: rgba(255,255,255,.08);
  border-color: rgba(255,255,255,.18);
}
.actBtn--primary{
  background: linear-gradient(135deg, rgba(16,185,129,.30), rgba(52,211,153,.12));
  border-color: rgba(16,185,129,.45);
}

/* Search / filter row */
.actTools{
  margin-top: 14px;
  display:flex;
  gap: 10px;
  flex-wrap: wrap;
  align-items:center;
}
.actInput{
  flex: 1;
  min-width: 240px;
  padding: 12px 14px;
  border-radius: 16px;
  border: 1px solid rgba(255,255,255,.14);
  background: rgba(255,255,255,.05);
  color: var(--txt);
  outline: none;
}
.actInput:focus{
  border-color: rgba(16,185,129,.55);
  box-shadow: 0 0 0 4px rgba(16,185,129,.14);
}

/* List */
.actList{
  margin-top: 14px;
  display:grid;
  grid-template-columns: repeat(12, minmax(0,1fr));
  gap: 12px;
}

.actCard{
  grid-column: span 6;
  border-radius: 22px;
  border: 1px solid rgba(255,255,255,.12);
  background: rgba(255,255,255,.05);
  box-shadow: var(--shadow);
  overflow:hidden;
  position:relative;
  padding: 18px;
  display:flex;
  gap: 14px;
}
.actCard::before{
  content:"";
  position:absolute; left:0; top:0; bottom:0;
  width:4px;
  background: linear-gradient(180deg, rgba(16,185,129,.95), rgba(52,211,153,.55));
}

.actCard__meta{
  width: 118px;
  flex-shrink:0;
  padding-left: 6px;
}

.actDate{
  display:inline-flex;
  flex-direction: column;
  gap: 4px;
  padding: 10px 10px;
  border-radius: 18px;
  border: 1px solid rgba(255,255,255,.12);
  background: rgba(0,0,0,.18);
}
.actDate__day{
  font-size: 22px;
  font-weight: 950;
  line-height: 1;
}
.actDate__month{
  font-size: 12px;
  letter-spacing: .08em;
  text-transform: uppercase;
  color: rgba(229,231,235,.78);
}
.actDate__year{
  font-size: 12px;
  color: rgba(229,231,235,.65);
}

.actCard__body{
  flex:1;
  min-width: 0;
}

.actTitle{
  margin: 0 0 6px;
  font-weight: 950;
  letter-spacing: .2px;
  font-size: 16px;
}
.actDesc{
  margin: 0;
  color: rgba(229,231,235,.80);
  line-height: 1.7;
  font-size: 13px;
}

.actBadges{
  margin-top: 10px;
  display:flex;
  gap: 10px;
  flex-wrap: wrap;
  align-items:center;
}

.actPill{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding: 9px 12px;
  border-radius: 999px;
  border: 1px solid rgba(255,255,255,.14);
  background: rgba(255,255,255,.06);
  font-weight: 850;
  font-size: 12px;
  color: rgba(229,231,235,.92);
}

.actPill--cat{
  border-color: rgba(16,185,129,.35);
  background: rgba(16,185,129,.12);
  color: rgba(167,243,208,.95);
}

.actActions{
  margin-top: 12px;
  display:flex;
  gap: 10px;
  flex-wrap: wrap;
}

.actChip{
  display:inline-flex; align-items:center; justify-content:center;
  padding: 10px 13px;
  border-radius: 999px;
  border: 1px solid rgba(255,255,255,.14);
  background: rgba(255,255,255,.06);
  font-weight: 900;
  cursor:pointer;
  transition: transform .12s ease, background .12s ease, border-color .12s ease;
}
.actChip:hover{
  transform: translateY(-1px);
  background: rgba(255,255,255,.08);
  border-color: rgba(255,255,255,.18);
}
.actChip--green{
  border-color: rgba(16,185,129,.40);
  background: rgba(16,185,129,.14);
  color: rgba(167,243,208,.95);
}

.actEmpty{
  margin-top: 14px;
  border-radius: 22px;
  border: 1px solid rgba(255,255,255,.12);
  background: rgba(255,255,255,.05);
  box-shadow: var(--shadow);
  padding: 18px;
  color: rgba(229,231,235,.78);
}

/* Reveal */
.reveal{
  opacity:0;
  transform: translateY(10px);
  transition: opacity 520ms ease, transform 520ms ease;
}
.reveal.is-in{opacity:1; transform: translateY(0);}

@media (max-width: 980px){
  .actCard{grid-column: span 12;}
  .actCard{flex-direction: column;}
  .actCard__meta{width: auto;}
}
</style>

<main class="act">
  <div class="act__container">

    <section class="actHero reveal">
      <div class="actHero__left">
        <span class="actKicker">AEEJ • Activités</span>
        <h1>Nos activités tout au long de l’année</h1>
        <p>
          Un aperçu simple et clair : chaque activité, sa date, et un accès direct à la galerie pour revivre les meilleurs moments.
        </p>
      </div>

      <div class="actHero__right">
        <a class="actBtn actBtn--primary" href="{{ route('galerie') }}">Ouvrir la galerie</a>
      </div>
    </section>

    <div class="actTools reveal">
      <input class="actInput" type="search"
             placeholder="Rechercher (libellé, catégorie, date)…"
             data-act-search>
      <button class="actBtn" type="button" data-act-reset>Réinitialiser</button>
    </div>

    @if(isset($activites) && $activites->count() > 0)
      <section class="actList" aria-label="Liste des activités">
        @foreach($activites as $activite)
          @php
            $date = $activite->date; // cast en date dans ton modèle
            $day = $date ? $date->format('d') : '—';
            $month = $date ? $date->translatedFormat('M') : 'Date';
            $year = $date ? $date->format('Y') : '—';

            $category = $activite->categorie ?: null;

            // Redirection galerie:
            // 1) Si tes catégories galerie = catégories d’activités, garde cette ligne.
            // 2) Sinon, remplace par: $galleryUrl = route('galerie');
            $galleryUrl = $category
              ? route('galerie', ['category' => $category])
              : route('galerie');

            $searchText = trim(
              ($activite->libelle ?? '') . ' ' .
              ($activite->categorie ?? '') . ' ' .
              ($date ? $date->format('d/m/Y') : '')
            );
          @endphp

          <article class="actCard reveal"
                   data-act-card
                   data-search="{{ strtolower($searchText) }}"
                   data-gallery-url="{{ $galleryUrl }}">
            <div class="actCard__meta">
              <div class="actDate" title="Date de l’activité">
                <div class="actDate__day">{{ $day }}</div>
                <div class="actDate__month">{{ $month }}</div>
                <div class="actDate__year">{{ $year }}</div>
              </div>
            </div>

            <div class="actCard__body">
              <h3 class="actTitle">{{ $activite->libelle }}</h3>

              <div class="actBadges">
                @if($category)
                  <span class="actPill actPill--cat">Catégorie : {{ $category }}</span>
                @else
                  <span class="actPill">Catégorie : —</span>
                @endif

                <span class="actPill">
                  {{ $date ? 'Le ' . $date->format('d/m/Y') : 'Date non renseignée' }}
                </span>
              </div>

              <div class="actActions">
                <button class="actChip actChip--green" type="button" data-open-gallery>
                  Voir les photos
                </button>
                <button class="actChip" type="button" data-copy-title>
                  Copier le libellé
                </button>
              </div>
            </div>
          </article>
        @endforeach
      </section>

      <div style="margin-top:14px;" class="reveal">
        {{-- Si tu utilises la pagination --}}
        @if(method_exists($activites, 'links'))
          {{ $activites->links() }}
        @endif
      </div>
    @else
      <div class="actEmpty reveal">
        Aucune activité disponible pour le moment.
      </div>
    @endif

  </div>
</main>

<script>
(() => {
  // ===== Reveal on scroll =====
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
    }, {threshold: 0.16});

    items.forEach(el => obs.observe(el));
  }

  // ===== Search filter =====
  function initSearch(){
    const input = document.querySelector('[data-act-search]');
    const reset = document.querySelector('[data-act-reset]');
    const cards = Array.from(document.querySelectorAll('[data-act-card]'));

    if (!input || !cards.length) return;

    function apply(){
      const q = (input.value || '').trim().toLowerCase();
      cards.forEach(card => {
        const hay = card.getAttribute('data-search') || '';
        card.style.display = (!q || hay.includes(q)) ? '' : 'none';
      });
    }

    input.addEventListener('input', apply);
    if (reset){
      reset.addEventListener('click', () => {
        input.value = '';
        apply();
      });
    }
  }

  // ===== Redirect to galerie =====
  function initGalleryButtons(){
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-open-gallery]');
      if (!btn) return;

      const card = btn.closest('[data-act-card]');
      if (!card) return;

      const url = card.getAttribute('data-gallery-url');
      if (url) window.location.href = url;
    });
  }

  // ===== Small UX: copy libellé =====
  function initCopyTitle(){
    document.addEventListener('click', async (e) => {
      const btn = e.target.closest('[data-copy-title]');
      if (!btn) return;

      const card = btn.closest('[data-act-card]');
      if (!card) return;

      const title = card.querySelector('.actTitle')?.textContent?.trim();
      if (!title) return;

      try{
        await navigator.clipboard.writeText(title);
        btn.textContent = "Copié !";
        setTimeout(() => btn.textContent = "Copier le libellé", 900);
      }catch(err){
        // fallback simple
        alert("Copie impossible sur ce navigateur. Libellé: " + title);
      }
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    initReveal();
    initSearch();
    initGalleryButtons();
    initCopyTitle();
  });
})();
</script>
@endsection
