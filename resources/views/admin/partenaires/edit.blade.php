@extends('layouts.admin')

@section('title', 'Admin • Modifier un partenaire')
@section('header', 'Édition partenaire')

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Modifier le partenaire</h1>
            <p class="admDash__sub">{{ $partenaire->nom }}</p>
        </div>
        <x-adm-back :href="route('admin.partenaires.index')" />
    </div>

    <div class="admGrid">
        <div class="admPanel" style="grid-column: span 4;">
            <div class="admPanel__head"><h2 class="admPanel__h text-white">Logo actuel</h2></div>
            <div class="admPanel__body" style="background:#fff; text-align:center; padding:24px; border-radius:0 0 16px 16px;">
                <img src="{{ $partenaire->logo_url }}" alt="{{ $partenaire->nom }}" style="max-width:100%; max-height:220px; object-fit:contain;">
            </div>
        </div>

        <div class="admPanel" style="grid-column: span 8;">
            <div class="admPanel__body">
                <form method="POST" action="{{ route('admin.partenaires.update', $partenaire) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    @include('admin.partenaires._form')

                    <div style="margin-top:24px; display:flex; gap:12px;">
                        <button type="submit" class="btn" style="background:#3b82f6; color:#fff; border-radius:12px; padding:12px 30px; font-weight:800; border:none; cursor:pointer;">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
