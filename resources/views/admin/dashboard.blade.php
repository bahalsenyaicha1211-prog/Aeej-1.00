@extends('layouts.admin')

@section('title', 'Admin • Dashboard')
@section('header', 'Tableau de bord Admin')

@section('styles')
<style>
    /* Global Dashboard Impact */
    .admDash { display: flex; flex-direction: column; gap: 24px; color: #fff; }
    
    /* Titre & Sous-titre */
    .admDash__title { font-size: 28px; font-weight: 900; margin: 0; background: linear-gradient(to right, #fff, #94a3b8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .admDash__sub { color: #94a3b8; font-size: 14px; margin-top: 4px; }

    /* Grille de KPI (Haut) */
    .admKpiGrid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }
    .admKpi { 
        background: rgba(255, 255, 255, 0.03); 
        border: 1px solid rgba(255, 255, 255, 0.08); 
        border-radius: 20px; padding: 20px; 
        position: relative; overflow: hidden;
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease;
    }
    .admKpi:hover { transform: translateY(-5px); border-color: rgba(34, 197, 94, 0.4); }
    .admKpi::after { content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(45deg, transparent, rgba(34, 197, 94, 0.05)); pointer-events: none; }
    .admKpi__label { font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #94a3b8; }
    .admKpi__value { font-size: 32px; font-weight: 900; margin: 8px 0; }
    .admKpi__meta { font-size: 12px; color: #4ade80; font-weight: 600; }

    /* Petite animation d'entrée, en cascade */
    @keyframes admKpiIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
    .admKpi { animation: admKpiIn .45s ease both; }
    .admKpi:nth-child(1) { animation-delay: .03s; }
    .admKpi:nth-child(2) { animation-delay: .09s; }
    .admKpi:nth-child(3) { animation-delay: .15s; }
    .admKpi:nth-child(4) { animation-delay: .21s; }

    /* Grille des Panels (Milieu) */
    .admGrid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 20px; }
    .admPanel { 
        grid-column: span 6; 
        background: rgba(15, 23, 42, 0.6); 
        border: 1px solid rgba(255, 255, 255, 0.1); 
        border-radius: 24px; overflow: hidden; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .admPanel--full { grid-column: span 12; }

    .admPanel__head { padding: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); background: rgba(255, 255, 255, 0.02); }
    .admPanel__h { font-size: 16px; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 10px; }
    .admPanel__p { font-size: 12px; color: #64748b; margin-top: 4px; }
    .admPanel__body { padding: 20px; }

    /* Lignes de données */
    .admRows { display: flex; flex-direction: column; gap: 10px; }
    .admRow { 
        display: flex; justify-content: space-between; align-items: center; 
        padding: 12px 16px; border-radius: 14px; 
        background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05);
        transition: background 0.2s;
    }
    .admRow:hover { background: rgba(255, 255, 255, 0.06); }
    .admRow__label { font-size: 14px; font-weight: 600; color: #e2e8f0; }
    .admRow__value { background: #22c55e; color: #fff; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 800; }

    /* Sexe Bar Impact */
    .genderBar__container { margin-top: 10px; height: 8px; background: rgba(236, 72, 153, 0.2); border-radius: 10px; overflow: hidden; display: flex; }
    .genderBar__fill { height: 100%; background: #3b82f6; transition: width 1s ease-in-out; }

    /* Chips pour les communautés */
    .admCountry__grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px; margin-top: 15px; }
    .admChip { background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.05); padding: 10px; border-radius: 12px; display: flex; justify-content: space-between; }
    .admChip__label { font-size: 12px; color: #94a3b8; }
    .admChip__value { color: #fff; font-weight: 800; }

    /* Responsive */
    @media (max-width: 1024px) { .admPanel { grid-column: span 12; } }

    /* Mobile : Membres+Pays sur une ligne, Hommes+Femmes sur une autre,
       au lieu d'empiler 4 cartes pleine largeur (espace gaspillé). */
    @media (max-width: 640px) {
        .admKpiGrid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .admKpi { padding: 16px; border-radius: 16px; }
        .admKpi__value { font-size: 26px; margin: 6px 0; }
        .admKpi__label { font-size: 10px; letter-spacing: 1px; }
    }
</style>
@endsection

@section('content')
<div class="admDash">
    {{-- Header --}}
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Tableau de bord</h1>
            <p class="admDash__sub text-white">Analyse en temps réel de votre communauté.</p>
        </div>
    </div>

    {{-- 1. Section KPI --}}
    <div class="admKpiGrid">
        @php
            // Ordre pensé pour le mobile (grille 2 colonnes) :
            // ligne 1 = Membres / Pays, ligne 2 = Hommes / Femmes.
            $kpis = [
                ['Membres', $stats['total_membres'] ?? 0, 'Inscrits', 'blue'],
                ['Pays', $stats['pays'] ?? 0, 'Référencés', 'purple'],
                ['Hommes', $stats['hommes'] ?? 0, 'Sexe M', 'green'],
                ['Femmes', $stats['femmes'] ?? 0, 'Sexe F', 'pink'],
            ];
        @endphp
        @foreach($kpis as $kpi)
        <div class="admKpi">
            <div class="admKpi__label text-white">{{ $kpi[0] }}</div>
            <div class="admKpi__value text-white">{{ $kpi[1] }}</div>
            <div class="admKpi__meta text-white">{{ $kpi[2] }}</div>
        </div>
        @endforeach
    </div>

    {{-- 2. Grille Principale --}}
    <div class="admGrid">
        {{-- Sexe par Pays (Prend plus de place car important) --}}
        <div class="admPanel admPanel--full">
            <div class="admPanel__head">
                <h2 class="admPanel__h text-white">🌍 Répartition Hommes/Femmes par Pays</h2>
                <p class="admPanel__p text-white">Analyse démographique géographique.</p>
            </div>
            <div class="admPanel__body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    @foreach($sexeParPays as $pays => $group)
                        @php
                            $m = $group->where('sexe', 'M')->first()->total ?? 0;
                            $f = $group->where('sexe', 'F')->first()->total ?? 0;
                            $total = $m + $f;
                            $percM = $total > 0 ? ($m / $total) * 100 : 0;
                        @endphp
                        <div class="admRow" style="flex-direction: column; align-items: stretch;">
                            <div style="display:flex; justify-content: space-between; margin-bottom: 8px;">
                                <span class="font-bold text-white">{{ $pays }}</span>
                                <span style="font-size: 12px; color: #94a3b8;">Total: {{ $total }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 5px;">
                                <span style="color: #3b82f6;">M: {{ $m }}</span>
                                <span style="color: #ec4899;">F: {{ $f }}</span>
                            </div>
                            <div class="genderBar__container">
                                <div class="genderBar__fill" style="width: {{ $percM }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Membres par Pays --}}
        <div class="admPanel">
            <div class="admPanel__head">
                <h2 class="admPanel__h text-white">📊 Top Pays</h2>
            </div>
            <div class="admPanel__body">
                <div class="admRows">
                    @foreach($parPays as $row)
                        <div class="admRow">
                            <span class="admRow__label text-white">{{ $row->label }}</span>
                            <span class="admRow__value text-white">{{ $row->total }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Membres par Année --}}
        <div class="admPanel">
            <div class="admPanel__head">
                <h2 class="admPanel__h text-white">📅 Croissance annuelle</h2>
            </div>
            <div class="admPanel__body">
                <div class="admRows">
                    @foreach($parAnnee as $row)
                        <div class="admRow">
                            <span class="admRow__label text-white">Année {{ $row->label }}</span>
                            <span class="admRow__value text-white" style="background:#8b5cf6">{{ $row->total }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Communautés par pays (Full Width) --}}
    <div class="admPanel admPanel--full">
        <div class="admPanel__head">
            <h2 class="admPanel__h text-white">🏘️ Communautés par département</h2>
            <p class="admPanel__p text-white">Détail des membres par département au sein de chaque pays.</p>
        </div>
        <div class="admPanel__body">
            @foreach($communauteParPays as $pays => $items)
                <div style="margin-bottom: 30px;">
                    <h3 style="font-size: 14px; color: #4ade80; border-left: 3px solid #4ade80; padding-left: 10px; margin-bottom: 15px;">{{ $pays }}</h3>
                    <div class="admCountry__grid">
                        @foreach($items as $it)
                            <div class="admChip">
                                <span class="admChip__label text-white">{{ $it->communaute }}</span>
                                <span class="admChip__value text-white">{{ $it->total }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection