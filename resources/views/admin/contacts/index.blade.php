@extends('layouts.admin')

@section('title', 'Admin • Contacts')
@section('header', 'Personnes à contacter')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Contacts</h1>
            <p class="admDash__sub">Gérez les personnes affichées sur la page publique « Contact » (photo, fonction, coordonnées).</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800; text-decoration: none;" href="{{ route('admin.contacts.create') }}">
            + Ajouter une personne
        </a>
    </div>

    @if(session('success'))
        <div style="margin-bottom:14px; padding:10px 14px; border-radius:10px; background:rgba(34,197,94,0.08); border:1px solid rgba(34,197,94,0.25); color:#4ade80; font-size:13px; font-weight:600;">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if($personnes->count() === 0)
        <div class="admPanel admPanel--full" style="text-align:center; padding:50px;">
            <p style="color:#64748b;">Aucune personne à contacter pour le moment.</p>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px;">
            @foreach($personnes as $p)
                <div class="admPanel" style="grid-column: span 1; {{ $p->is_highlighted ? 'border-color: rgba(245,158,11,0.5);' : '' }}">
                    <div style="position:relative; height:220px; background:#0b1220;">
                        <img src="{{ $p->photo_url }}" alt="{{ $p->nom }}" style="width:100%; height:100%; object-fit:cover;">
                        <div style="position:absolute; top:10px; left:10px; display:flex; gap:6px; flex-wrap:wrap;">
                            <span style="background: {{ $p->is_published ? 'rgba(34,197,94,0.9)' : 'rgba(239,68,68,0.9)' }}; color:#fff; padding:4px 10px; border-radius:20px; font-size:10px; font-weight:900; text-transform:uppercase;">
                                {{ $p->is_published ? 'Publié' : 'Masqué' }}
                            </span>
                            @if($p->is_highlighted)
                                <span style="background: rgba(245,158,11,0.9); color:#fff; padding:4px 10px; border-radius:20px; font-size:10px; font-weight:900; text-transform:uppercase;">
                                    ★ Mis en avant
                                </span>
                            @endif
                        </div>
                        <div style="position:absolute; top:10px; right:10px; background:rgba(0,0,0,.55); color:#fff; width:26px; height:26px; border-radius:999px; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:800;">
                            {{ $p->position }}
                        </div>
                    </div>

                    <div class="admPanel__body" style="padding:15px;">
                        <div style="font-weight:800; color:#fff; font-size:14px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->nom }}</div>
                        <div style="font-size:11px; color:#4ade80; font-weight:700; text-transform:uppercase; margin:4px 0 10px;">{{ $p->poste }}</div>

                        <div style="font-size:12px; color:#94a3b8; margin-bottom:14px; display:flex; flex-direction:column; gap:2px;">
                            @if($p->telephone)<span><x-icon name="phone" style="width:12px;height:12px" /> {{ $p->telephone }}</span>@endif
                            @if($p->email)<span style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><x-icon name="mail" style="width:12px;height:12px" /> {{ $p->email }}</span>@endif
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px;">
                            <form method="POST" action="{{ route('admin.contacts.toggle', $p) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="admQuick__btn" style="width:100%; font-size:11px;">{{ $p->is_published ? 'Masquer' : 'Publier' }}</button>
                            </form>
                            <a href="{{ route('admin.contacts.edit', $p) }}" class="admQuick__btn" style="text-decoration:none; text-align:center; font-size:11px; border-color:rgba(59,130,246,0.3); color:#60a5fa;">Modifier</a>
                            <form method="POST" action="{{ route('admin.contacts.destroy', $p) }}" style="grid-column: span 2;" onsubmit="return confirm('Supprimer « {{ $p->nom }} » ?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="admQuick__btn" style="width:100%; font-size:11px; color:#f87171; border-color:rgba(239,68,68,0.2);">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
