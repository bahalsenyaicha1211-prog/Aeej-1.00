@extends('layouts.admin')

@section('title', 'Admin • Message')
@section('header', 'Message de contact')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">{{ $message->prenom }} {{ $message->nom }}</h1>
            <p class="admDash__sub">Reçu le {{ $message->created_at->format('d M Y à H:i') }}</p>
        </div>
        <a class="admQuick__btn" href="{{ route('admin.messages.index') }}" style="text-decoration:none;">← Retour</a>
    </div>

    <div class="admGrid">
        <div class="admPanel">
            <div class="admPanel__head"><h2 class="admPanel__h text-white">Coordonnées</h2></div>
            <div class="admPanel__body">
                <div class="admRows">
                    <div class="admRow"><span style="color:#64748b;">Email</span> <span class="text-white font-bold">{{ $message->email }}</span></div>
                    <div class="admRow"><span style="color:#64748b;">Téléphone</span> <span class="text-white font-bold">{{ $message->telephone ?: '—' }}</span></div>
                </div>
            </div>
        </div>

        <div class="admPanel admPanel--full">
            <div class="admPanel__head"><h2 class="admPanel__h text-white">Message</h2></div>
            <div class="admPanel__body">
                <p class="text-white" style="white-space: pre-line; line-height:1.6;">{{ $message->message }}</p>
            </div>
        </div>

        <div class="admPanel admPanel--full" style="border-color: rgba(239, 68, 68, 0.2); background: rgba(239, 68, 68, 0.02);">
            <div class="admPanel__body" style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <h3 style="color:#f87171; font-weight:800; margin:0;">Zone critique</h3>
                    <p style="color:#64748b; font-size:12px; margin:0;">La suppression de ce message est irréversible.</p>
                </div>
                <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Supprimer ce message ?');">
                    @csrf @method('DELETE')
                    <button class="btn" style="background:#dc2626; color:#fff; border-radius:10px; padding:10px 20px; font-weight:800; cursor:pointer; border:none;">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
