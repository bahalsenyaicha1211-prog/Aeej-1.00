@extends('layouts.admin')

@section('title', 'Admin • Pays')
@section('header', 'Gestion géographique')

@section('content')
<div class="admDash">
    {{-- Header --}}
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Référentiel des Pays</h1>
            <p class="admDash__sub">Gérez la liste des pays de résidence disponibles pour les membres.</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800; text-decoration: none;" href="{{ route('admin.pays.create') }}">
            + Nouveau Pays
        </a>
    </div>

    {{-- Table --}}
    <div class="admPanel admPanel--full">
        <div class="admPanel__body" style="padding: 0;">
            <div class="table-wrap">
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: rgba(255,255,255,0.02); text-align: left;">
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase; width: 100px;">ID</th>
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase;">Nom du pays</th>
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admRows">
                        @forelse($pays as $p)
                        <tr class="admRow" style="display: table-row; background: transparent; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 18px;">
                                <span style="color: #3b82f6; font-family: monospace; font-weight: 700; background: rgba(59, 130, 246, 0.1); padding: 4px 10px; border-radius: 8px;">
                                    #{{ $p->idpays }}
                                </span>
                            </td>
                            <td style="padding: 18px;">
                                <div style="font-weight: 800; color: #fff; font-size: 16px; display: flex; align-items: center; gap: 10px;">
                                    🌍 {{ $p->nom }}
                                </div>
                            </td>
                            {{-- AFFICHAGE DE LA SIGNATURE --}}
                            <td style="padding: 18px;">
                                <span style="color: #22c55e; font-weight: 800; background: rgba(34, 197, 94, 0.1); padding: 4px 8px; border-radius: 6px;">
                                    {{ $p->signature ?? '??' }}
                                </span>
                            </td>
                      
                            <td style="padding: 18px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a class="admQuick__btn" href="{{ route('admin.pays.edit', $p) }}" style="padding: 6px 15px; font-size: 12px; text-decoration: none; border-color: rgba(59, 130, 246, 0.3); color: #60a5fa;">
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('admin.pays.destroy', $p) }}" onsubmit="return confirm('Supprimer ce pays ?');">
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
                            <td colspan="3" style="padding: 60px; text-align: center; color: #64748b;">
                                Aucun pays dans la base de données.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div style="margin-top: 20px;">
        {{ $pays->links() ?? '' }}
    </div>
</div>
@endsection