@extends('layouts.admin')

@section('title', 'Admin • Messages')
@section('header', 'Messages de contact')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Messages reçus</h1>
            <p class="admDash__sub">Messages envoyés depuis le formulaire de contact du site public.</p>
        </div>
    </div>

    <form method="GET" style="margin-bottom: 16px; display:flex; gap:10px; align-items:center;">
        <input class="input" type="text" name="q" value="{{ $q }}" placeholder="Rechercher par nom ou email..." style="max-width:320px; width:100%;">
        <button class="admQuick__btn" type="submit">Rechercher</button>
        @if($q !== '')
            <a class="admQuick__btn" href="{{ route('admin.messages.index') }}" style="text-decoration:none;">Réinitialiser</a>
        @endif
    </form>

    <div class="admPanel admPanel--full">
        <div class="admPanel__body" style="padding: 0;">
            <div class="table-wrap">
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: rgba(255,255,255,0.02); text-align: left;">
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase;">Statut</th>
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase;">Expéditeur</th>
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase;">Message</th>
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase;">Reçu le</th>
                            <th style="padding: 18px; color: #64748b; font-size: 11px; text-transform: uppercase; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="admRows">
                        @forelse($messages as $m)
                        <tr class="admRow" style="display: table-row; background: transparent; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <td style="padding: 18px;">
                                <span style="padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 900; text-transform: uppercase; background: {{ $m->is_read ? 'rgba(100,116,139,0.1)' : 'rgba(34,197,94,0.1)' }}; color: {{ $m->is_read ? '#94a3b8' : '#4ade80' }};">
                                    {{ $m->is_read ? 'Lu' : 'Nouveau' }}
                                </span>
                            </td>
                            <td style="padding: 18px;">
                                <div style="font-weight: 600; color: #e2e8f0;">{{ $m->prenom }} {{ $m->nom }}</div>
                                <div style="color: #64748b; font-size: 12px;">{{ $m->email }}</div>
                            </td>
                            <td style="padding: 18px; max-width: 350px;">
                                <div style="color: #94a3b8; font-size: 13px;">{{ \Illuminate\Support\Str::limit($m->message, 80) }}</div>
                            </td>
                            <td style="padding: 18px;">
                                <div style="color: #64748b; font-size: 12px;">{{ $m->created_at->format('d M Y • H:i') }}</div>
                            </td>
                            <td style="padding: 18px; text-align: right;">
                                <a class="admQuick__btn" href="{{ route('admin.messages.show', $m) }}" style="text-decoration: none;">Voir</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="padding: 60px; text-align: center; color: #64748b;">{{ $q !== '' ? 'Aucun message ne correspond à « '.$q.' ».' : 'Aucun message reçu pour le moment.' }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div style="margin-top: 20px;">
        {{ $messages->links('vendor.pagination.admin') }}
    </div>
</div>
@endsection
