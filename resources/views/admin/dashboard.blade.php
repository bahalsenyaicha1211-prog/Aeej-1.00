{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Admin • Dashboard')
@section('header', 'Tableau de bord Admin')

@section('styles')
<style>
/* ===== DASHBOARD ONLY (isolé) ===== */
.admDash{display:flex; flex-direction:column; gap:14px}

.admDash__head{
  display:flex; align-items:flex-start; justify-content:space-between;
  gap:12px; flex-wrap:wrap;
}
.admDash__title{margin:0; font-size:22px; font-weight:900; letter-spacing:.2px}
.admDash__sub{margin:6px 0 0; color:rgba(229,231,235,.72); font-size:13px; line-height:1.5}

/* KPI cards */
.admKpiGrid{
  display:grid;
  grid-template-columns: repeat(12, minmax(0,1fr));
  gap:12px;
}
.admKpi{
  grid-column: span 4;
  border:1px solid rgba(255,255,255,.12);
  border-radius:18px;
  background: rgba(255,255,255,.04);
  box-shadow: 0 14px 34px rgba(0,0,0,.30);
  padding:14px;
  position:relative;
  overflow:hidden;
}
.admKpi::before{
  content:"";
  position:absolute; left:0; top:0; bottom:0;
  width:4px;
  background: linear-gradient(180deg, rgba(34,197,94,.95), rgba(22,163,74,.55));
}
.admKpi__top{display:flex; align-items:center; justify-content:space-between; gap:10px}
.admKpi__label{
  font-size:11px; letter-spacing:.10em; text-transform:uppercase;
  color: rgba(229,231,235,.70);
}
.admKpi__value{margin-top:10px; font-size:30px; font-weight:950; letter-spacing:.2px}
.admKpi__meta{margin-top:4px; color: rgba(229,231,235,.68); font-size:12px}

/* Panels grid */
.admGrid{
  display:grid;
  grid-template-columns: repeat(12, minmax(0,1fr));
  gap:12px;
}
.admPanel{
  grid-column: span 6;
  border:1px solid rgba(255,255,255,.12);
  border-radius:18px;
  background: rgba(255,255,255,.04);
  box-shadow: 0 18px 40px rgba(0,0,0,.33);
  overflow:hidden;
}
.admPanel__head{
  padding:14px;
  border-bottom:1px solid rgba(255,255,255,.08);
  background: rgba(255,255,255,.02);
}
.admPanel__h{margin:0; font-weight:950; letter-spacing:.2px}
.admPanel__p{margin:6px 0 0; color: rgba(229,231,235,.72); font-size:12px; line-height:1.5}
.admPanel__body{padding:14px}

/* list rows */
.admRows{display:flex; flex-direction:column; gap:10px}
.admRow{
  display:flex; justify-content:space-between; align-items:center;
  gap:12px;
  padding:12px;
  border:1px solid rgba(255,255,255,.10);
  border-radius:16px;
  background: rgba(0,0,0,.10);
}
.admRow__label{font-weight:850; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap}
.admRow__value{
  border:1px solid rgba(34,197,94,.28);
  background: rgba(34,197,94,.10);
  color: rgba(187,247,208,.95);
  border-radius:999px;
  padding:8px 10px;
  font-size:12px;
  white-space:nowrap;
}
.empty{padding: 12px; color: rgba(229,231,235,.70); text-align:center}

/* Communautés par pays */
.admCountries{
  border:1px solid rgba(255,255,255,.12);
  border-radius:18px;
  background: rgba(255,255,255,.04);
  box-shadow: 0 18px 40px rgba(0,0,0,.33);
  overflow:hidden;
}
.admCountries__head{
  padding:14px;
  border-bottom:1px solid rgba(255,255,255,.08);
  background: rgba(255,255,255,.02);
}
.admCountries__body{padding:14px}
.admCountry{margin-bottom:14px}
.admCountry__name{
  font-weight:950;
  margin:0 0 10px;
  letter-spacing:.2px;
}
.admCountry__grid{
  display:grid;
  grid-template-columns: repeat(12, minmax(0,1fr));
  gap:10px;
}
.admChip{
  grid-column: span 4;
  padding:10px 12px;
  border-radius:16px;
  border:1px solid rgba(255,255,255,.10);
  background: rgba(0,0,0,.10);
  display:flex; justify-content:space-between; gap:10px;
}
.admChip__label{font-weight:850; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap}
.admChip__value{
  color: rgba(187,247,208,.95);
  font-weight:950;
}

/* ===== Accès rapides (Galerie + Admins) ===== */
.admQuick{
  display:grid;
  grid-template-columns: repeat(12, minmax(0,1fr));
  gap:12px;
}
.admQuick__card{
  grid-column: span 6;
  border:1px solid rgba(255,255,255,.12);
  border-radius:18px;
  background: rgba(255,255,255,.04);
  box-shadow: 0 18px 40px rgba(0,0,0,.33);
  overflow:hidden;
}
.admQuick__head{
  padding:14px;
  border-bottom:1px solid rgba(255,255,255,.08);
  background: rgba(255,255,255,.02);
}
.admQuick__body{
  padding:14px;
  display:flex;
  gap:10px;
  flex-wrap:wrap;
  align-items:center;
}
.admQuick__btn{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  padding:10px 12px;
  border-radius:16px;
  text-decoration:none;
  color: rgba(229,231,235,.95);
  border:1px solid rgba(255,255,255,.12);
  background: rgba(0,0,0,.10);
  transition: transform .12s ease, background .12s ease, border-color .12s ease;
}
.admQuick__btn:hover{
  background: rgba(255,255,255,.06);
  border-color: rgba(255,255,255,.18);
  transform: translateY(-1px);
}
.admQuick__btn--green{
  border-color: rgba(34,197,94,.35);
  background: rgba(34,197,94,.10);
}
.admQuick__btn--green:hover{
  background: rgba(34,197,94,.14);
}

/* Responsive */
@media (max-width: 1200px){
  .admKpi{grid-column: span 6;}
  .admPanel{grid-column: span 12;}
  .admChip{grid-column: span 6;}
  .admQuick__card{grid-column: span 12;}
}
@media (max-width: 760px){
  .admKpi{grid-column: span 12;}
  .admChip{grid-column: span 12;}
  .admRow__label{white-space:normal}
}
</style>
@endsection

@section('content')

@php
  $u = auth()->user();
@endphp

<div class="admDash">

  <div class="admDash__head">
      <div>
          <h1 class="admDash__title">Tableau de bord</h1>
          <p class="admDash__sub">
              Statistiques globales et répartitions (pays, départements, années, sexe, communautés).
          </p>
      </div>
  </div>

  {{-- KPI --}}
  <div class="admKpiGrid">
      <div class="admKpi">
          <div class="admKpi__top"><div class="admKpi__label">Total membres</div></div>
          <div class="admKpi__value">{{ $stats['total_membres'] ?? 0 }}</div>
          <div class="admKpi__meta">Inscrits</div>
      </div>

      <div class="admKpi">
          <div class="admKpi__top"><div class="admKpi__label">Hommes</div></div>
          <div class="admKpi__value">{{ $stats['hommes'] ?? 0 }}</div>
          <div class="admKpi__meta">Sexe M</div>
      </div>

      <div class="admKpi">
          <div class="admKpi__top"><div class="admKpi__label">Femmes</div></div>
          <div class="admKpi__value">{{ $stats['femmes'] ?? 0 }}</div>
          <div class="admKpi__meta">Sexe F</div>
      </div>

      <div class="admKpi">
          <div class="admKpi__top"><div class="admKpi__label">Départements</div></div>
          <div class="admKpi__value">{{ $stats['departements'] ?? 0 }}</div>
          <div class="admKpi__meta">Référencés</div>
      </div>

      <div class="admKpi">
          <div class="admKpi__top"><div class="admKpi__label">Pays</div></div>
          <div class="admKpi__value">{{ $stats['pays'] ?? 0 }}</div>
          <div class="admKpi__meta">Référencés</div>
      </div>

      <div class="admKpi">
          <div class="admKpi__top"><div class="admKpi__label">Annonces</div></div>
          <div class="admKpi__value">{{ $stats['annonces'] ?? 0 }}</div>
          <div class="admKpi__meta">Total</div>
      </div>
  </div>

  
  {{-- Répartitions --}}
  <div class="admGrid">

      <div class="admPanel">
          <div class="admPanel__head">
              <h2 class="admPanel__h">Membres par pays</h2>
              <p class="admPanel__p">Répartition des membres par pays.</p>
          </div>
          <div class="admPanel__body">
              @if(!empty($parPays) && count($parPays))
                  <div class="admRows">
                      @foreach($parPays as $row)
                          <div class="admRow">
                              <div class="admRow__label">{{ $row->label }}</div>
                              <div class="admRow__value">{{ $row->total }}</div>
                          </div>
                      @endforeach
                  </div>
              @else
                  <div class="empty">Aucune donnée.</div>
              @endif
          </div>
      </div>

      <div class="admPanel">
          <div class="admPanel__head">
              <h2 class="admPanel__h">Membres par département</h2>
              <p class="admPanel__p">Répartition des membres par département.</p>
          </div>
          <div class="admPanel__body">
              @if(!empty($parDepartement) && count($parDepartement))
                  <div class="admRows">
                      @foreach($parDepartement as $row)
                          <div class="admRow">
                              <div class="admRow__label">{{ $row->label }}</div>
                              <div class="admRow__value">{{ $row->total }}</div>
                          </div>
                      @endforeach
                  </div>
              @else
                  <div class="empty">Aucune donnée.</div>
              @endif
          </div>
      </div>

      <div class="admPanel">
          <div class="admPanel__head">
              <h2 class="admPanel__h">Membres par année d’inscription</h2>
              <p class="admPanel__p">Évolution des adhésions par année.</p>
          </div>
          <div class="admPanel__body">
              @if(!empty($parAnnee) && count($parAnnee))
                  <div class="admRows">
                      @foreach($parAnnee as $row)
                          <div class="admRow">
                              <div class="admRow__label">{{ $row->label }}</div>
                              <div class="admRow__value">{{ $row->total }}</div>
                          </div>
                      @endforeach
                  </div>
              @else
                  <div class="empty">Aucune donnée.</div>
              @endif
          </div>
      </div>

      <div class="admPanel">
          <div class="admPanel__head">
              <h2 class="admPanel__h">Membres par sexe</h2>
              <p class="admPanel__p">Répartition par sexe (M/F).</p>
          </div>
          <div class="admPanel__body">
              @if(!empty($parSexe) && count($parSexe))
                  <div class="admRows">
                      @foreach($parSexe as $row)
                          <div class="admRow">
                              <div class="admRow__label">{{ $row->label }}</div>
                              <div class="admRow__value">{{ $row->total }}</div>
                          </div>
                      @endforeach
                  </div>
              @else
                  <div class="empty">Aucune donnée.</div>
              @endif
          </div>
      </div>

  </div>

  {{-- Communautés par pays (Pays -> Département) --}}
  <div class="admCountries">
      <div class="admCountries__head">
          <h2 class="admPanel__h">Communautés par pays</h2>
          <p class="admPanel__p">
              Détail par pays : nombre de membres par “communauté” (ici : département).
          </p>
      </div>
      <div class="admCountries__body">
          @if(!empty($communauteParPays) && count($communauteParPays))
              @foreach($communauteParPays as $pays => $items)
                  <div class="admCountry">
                      <h3 class="admCountry__name">{{ $pays }}</h3>
                      <div class="admCountry__grid">
                          @foreach($items as $it)
                              <div class="admChip">
                                  <div class="admChip__label">{{ $it->communaute }}</div>
                                  <div class="admChip__value">{{ $it->total }}</div>
                              </div>
                          @endforeach
                      </div>
                  </div>
              @endforeach
          @else
              <div class="empty">Aucune donnée.</div>
          @endif
      </div>
  </div>

</div>

@endsection
