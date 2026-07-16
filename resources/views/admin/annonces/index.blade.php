@extends('layouts.admin')

@section('title', 'Admin • Annonces')
@section('header', 'Gestion des communications')

@section('content')
<div class="admDash">
    {{-- Header --}}
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Annonces & Flashs</h1>
            <p class="admDash__sub">Gérez les messages diffusés sur le tableau de bord des membres.</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800; text-decoration: none;" href="{{ route('admin.annonces.create') }}">
            + Nouvelle Annonce
        </a>
    </div>

    {{-- Table --}}
    <div class="admPanel admPanel--full">
        <div class="admPanel__body" style="padding: 0;">
            <div class="table-wrap">
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: rgba(255,255,255,0.02); text-align: left;">
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase;">Statut</th>
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase;">Contenu (Extrait)</th>
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase;">Date de publication</th>
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admRows">
                        @forelse($annonces as $a)
                        <tr class="admRow" style="display: table-row; background: transparent; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 18px;">
                                <div style="display: flex; flex-direction: column; gap: 5px;">
                                    {{-- Badge Publié/Brouillon --}}
                                    <span style="padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 900; text-transform: uppercase; text-align: center; background: {{ $a->is_published ? 'rgba(34,197,94,0.1)' : 'rgba(245,158,11,0.1)' }}; color: {{ $a->is_published ? '#4ade80' : '#fbbf24' }}; border: 1px solid {{ $a->is_published ? 'rgba(34,197,94,0.2)' : 'rgba(245,158,11,0.2)' }};">
                                        {{ $a->is_published ? 'En ligne' : 'Brouillon' }}
                                    </span>
                                    {{-- Badge Épinglé --}}
                                    @if($a->is_pinned)
                                        <span style="padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 900; text-transform: uppercase; text-align: center; background: rgba(59,130,246,0.1); color: #60a5fa; border: 1px solid rgba(59,130,246,0.2);">
                                            📌 Épinglée
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 18px; max-width: 400px;">
                                <div style="font-weight: 600; color: #e2e8f0; font-size: 14px; line-height: 1.4;">
                                    {{ \Illuminate\Support\Str::limit($a->contenu, 90) }}
                                </div>
                            </td>
                            <td style="padding: 18px;">
                                <div style="color: #64748b; font-size: 12px; font-weight: 500;">
                                    {{ $a->published_at ? $a->published_at->format('d M Y • H:i') : '—' }}
                                </div>
                            </td>
                            <td style="padding: 18px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a class="admQuick__btn" href="{{ route('admin.annonces.edit', $a) }}" style="padding: 6px 15px; font-size: 12px; text-decoration: none; border-color: rgba(59,130,246,0.3); color: #60a5fa;">
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('admin.annonces.destroy', $a) }}" onsubmit="return confirm('Supprimer cette annonce ?');">
                                        @csrf @method('DELETE')
                                        <button class="admQuick__btn" style="border-color: rgba(239,68,68,0.3); color: #f87171; background: rgba(239,68,68,0.05); padding: 6px 15px; font-size: 12px; cursor: pointer;">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="padding: 60px; text-align: center; color: #64748b;">Aucune annonce diffusée pour le moment.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div style="margin-top: 20px;">
        {{ $annonces->links('vendor.pagination.admin') }}
    </div>
</div>
@endsection