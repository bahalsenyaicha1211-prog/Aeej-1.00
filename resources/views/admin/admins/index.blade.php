@extends('layouts.admin')

@section('title', 'Admin • Admins')
@section('header', 'Sécurité & Accès')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Gestion des Administrateurs</h1>
            <p class="admDash__sub">Contrôlez les accès à l'interface de gestion de l'association.</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800; text-decoration: none;" href="{{ route('admin.admins.create') }}">
            + Ajouter un admin
        </a>
    </div>

    <div class="admPanel admPanel--full">
        <div class="admPanel__body" style="padding: 0;">
            <div class="table-wrap">
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: rgba(255,255,255,0.02); text-align: left;">
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase;">Administrateur</th>
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase;">Email</th>
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase;">Rôle</th>
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase;">Création</th>
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admRows">
                        @forelse($admins as $a)
                        <tr class="admRow" style="display: table-row; background: transparent; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 18px;">
                                <div style="font-weight: 800; color: #fff;">{{ $a->name }}</div>
                                <div style="font-size: 11px; color: #64748b; font-family: monospace;">UID: {{ $a->id }}</div>
                            </td>
                            <td style="padding: 18px;">
                                <span style="color: #e2e8f0; font-family: monospace; font-size: 13px;">{{ $a->email }}</span>
                            </td>
                            <td style="padding: 18px;">
                                @if($a->is_super_admin)
                                    <span style="padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 900; background: rgba(139, 92, 246, 0.1); color: #a78bfa; border: 1px solid rgba(139, 92, 246, 0.2);">SUPER ADMIN</span>
                                @else
                                    <span style="padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 900; background: rgba(255, 255, 255, 0.05); color: #94a3b8; border: 1px solid rgba(255, 255, 255, 0.1);">ADMIN</span>
                                @endif
                            </td>
                            <td style="padding: 18px;">
                                <div style="color: #64748b; font-size: 12px;">{{ optional($a->created_at)->format('d/m/Y') }}</div>
                            </td>
                            <td style="padding: 18px; text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end; flex-wrap: wrap;">
                                    <a class="admQuick__btn" href="{{ route('admin.admins.edit', $a) }}" style="padding: 6px 12px; font-size: 12px; text-decoration: none;">Modifier</a>

                                    @if(auth()->user()->is_super_admin && $a->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.admins.toggleSuper', $a) }}">
                                            @csrf @method('PATCH')
                                            <button class="admQuick__btn" style="padding: 6px 12px; font-size: 11px; border-color: rgba(167, 139, 250, 0.3); color: #a78bfa;">
                                                {{ $a->is_super_admin ? 'Rétrograder' : 'Promouvoir Super' }}
                                            </button>
                                        </form>

                                        @if(!$a->is_super_admin)
                                        <form method="POST" action="{{ route('admin.admins.destroy', $a) }}" onsubmit="return confirm('Supprimer cet admin ?');">
                                            @csrf @method('DELETE')
                                            <button class="admQuick__btn" style="border-color: rgba(239,68,68,0.3); color: #f87171; background: rgba(239,68,68,0.05); padding: 6px 12px; font-size: 11px;">Supprimer</button>
                                        </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="padding: 50px; text-align: center; color: #64748b;">Aucun compte administrateur trouvé.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div style="margin-top: 20px;">{{ $admins->links('vendor.pagination.admin') }}</div>
</div>
@endsection