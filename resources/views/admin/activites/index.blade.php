@extends('layouts.admin')

@section('title', 'Admin • Activités')
@section('header', 'Journal des Activités')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Activités de l'Association</h1>
            <p class="admDash__sub">Historique et planification des événements et actions réalisées.</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800; text-decoration: none;" href="{{ route('admin.activites.create') }}">
            + Nouvelle Activité
        </a>
    </div>

    <form method="GET" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center;">
        <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher une activité..." style="max-width:320px; width:100%;">
        <button class="admQuick__btn" type="submit">Rechercher</button>
        @if($q !== '')
            <a class="admQuick__btn" href="{{ route('admin.activites.index') }}" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    <div class="admPanel admPanel--full">
        <div class="admPanel__body" style="padding: 0;">
            <div class="table-wrap">
                <table class="table table--compact-rows" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: rgba(255,255,255,0.02); text-align: left;">
                            <th style="color: #64748b; font-size: 11px; text-transform: uppercase; width: 140px;">Date</th>
                            <th style="color: #64748b; font-size: 11px; text-transform: uppercase;">Libellé de l'activité</th>
                            <th style="color: #64748b; font-size: 11px; text-transform: uppercase;">Catégorie</th>
                            <th style="color: #64748b; font-size: 11px; text-transform: uppercase; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admRows">
                        @forelse($activites as $a)
                        <tr class="admRow" style="background: transparent; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td>
                                <div style="color: #4ade80; font-family: monospace; font-weight: 700; background: rgba(74, 222, 128, 0.1); padding: 4px 10px; border-radius: 8px; font-size: 13px; display: inline-block;">
                                    {{ \Illuminate\Support\Carbon::parse($a->date)->format('d/m/Y') }}
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 800; color: #fff; font-size: 15px;">{{ $a->libelle }}</div>
                            </td>
                            <td>
                                <span style="padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; background: rgba(255,255,255,0.05); color: #94a3b8; border: 1px solid rgba(255,255,255,0.1);">
                                    {{ $a->categorie ?: 'Non classée' }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a class="admQuick__btn" href="{{ route('admin.activites.edit', $a) }}" style="padding: 6px 15px; font-size: 12px; text-decoration: none; border-color: rgba(59, 130, 246, 0.3); color: #60a5fa;">
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('admin.activites.destroy', $a) }}" onsubmit="return confirm('Supprimer cette activité ?');">
                                        @csrf @method('DELETE')
                                        <button class="admQuick__btn" style="border-color: rgba(239,68,68,0.3); color: #f87171; background: rgba(239,68,68,0.05); padding: 6px 15px; font-size: 12px; cursor: pointer;">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="padding: 60px; text-align: center; color: #64748b;">
                                <div style="font-size: 40px; margin-bottom: 10px;">📅</div>
                                {{ $q !== '' ? 'Aucune activité ne correspond à « '.$q.' ».' : 'Aucune activité enregistrée.' }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div style="margin-top: 20px;">
        {{ $activites->links('vendor.pagination.admin') }}
    </div>
</div>
@endsection