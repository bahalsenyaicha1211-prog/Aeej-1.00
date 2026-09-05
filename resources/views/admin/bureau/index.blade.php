@extends('layouts.admin')

@section('title', 'Gestion du Bureau')
@section('header', 'Structure du Bureau')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Gestion du Bureau</h1>
            <p class="admDash__sub">Organisez la hiérarchie et l'affichage des membres du bureau public.</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800;" href="{{ route('admin.bureau.create') }}">
            + Ajouter un membre
        </a>
    </div>

    <form method="GET" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center;">
        <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher par nom, matricule ou poste..." style="max-width:320px; width:100%;">
        <button class="admQuick__btn" type="submit">Rechercher</button>
        @if($q !== '')
            <a class="admQuick__btn" href="{{ route('admin.bureau.index') }}" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    <div class="admPanel admPanel--full">
        <div class="admPanel__body" style="padding: 0;">
            <div class="table-wrap">
                <table class="table table--compact-rows" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: rgba(255,255,255,0.02); text-align: left;">
                            <th style="color: #64748b; font-size: 11px; text-transform: uppercase;">Membre</th>
                            <th style="color: #64748b; font-size: 11px; text-transform: uppercase;">Poste / Mission</th>
                            <th style="color: #64748b; font-size: 11px; text-transform: uppercase; text-align: center;">Ordre</th>
                            <th style="color: #64748b; font-size: 11px; text-transform: uppercase;">Statut</th>
                            <th style="color: #64748b; font-size: 11px; text-transform: uppercase; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admRows">
                        @forelse($bureau as $b)
                        <tr class="admRow" style="background: transparent; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <img src="{{ $b->photo ? (str_starts_with($b->photo, 'http') ? $b->photo : asset('storage/'.$b->photo)) : asset('images/image1.JPG') }}"
                                         style="width: 45px; height: 45px; border-radius: 12px; object-fit: cover; border: 2px solid rgba(255,255,255,0.1);">
                                    <div>
                                        <div style="font-weight: 800; color: #fff;">{{ $b->membre?->prenom }} {{ $b->membre?->nom }}</div>
                                        <div style="font-size: 11px; color: #64748b;">Matricule: {{ $b->matricule }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-weight: 600; color: #e2e8f0;">{{ $b->poste }}</td>
                            <td style="text-align: center;">
                                <span style="background: rgba(255,255,255,0.05); padding: 4px 10px; border-radius: 8px; font-size: 12px; font-weight: 800;">
                                    #{{ $b->ordre }}
                                </span>
                            </td>
                            <td>
                                <span style="padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 900; text-transform: uppercase; background: {{ $b->is_actif ? 'rgba(34,197,94,0.1)' : 'rgba(239,68,68,0.1)' }}; color: {{ $b->is_actif ? '#4ade80' : '#f87171' }}; border: 1px solid {{ $b->is_actif ? 'rgba(34,197,94,0.2)' : 'rgba(239,68,68,0.2)' }};">
                                    {{ $b->is_actif ? '• Public' : '• Masqué' }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <a class="admQuick__btn" href="{{ route('admin.bureau.edit', $b) }}" style="padding: 6px 12px; font-size: 12px;">Modifier</a>
                                    <form method="POST" action="{{ route('admin.bureau.destroy', $b) }}" onsubmit="return confirm('Supprimer ce membre ?')">
                                        @csrf @method('DELETE')
                                        <button class="admQuick__btn" style="border-color: rgba(239,68,68,0.3); color: #f87171; background: rgba(239,68,68,0.05); padding: 6px 12px; font-size: 12px;">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="padding: 40px; text-align: center; color: #64748b;">{{ $q !== '' ? 'Aucun membre du bureau ne correspond à « '.$q.' ».' : 'Aucun membre dans le bureau.' }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div style="margin-top: 15px;">{{ $bureau->links('vendor.pagination.admin') }}</div>
</div>
@endsection