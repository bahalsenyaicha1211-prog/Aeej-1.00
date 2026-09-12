@extends('layouts.admin')

@section('title', 'Admin • Nouveau partenaire')
@section('header', 'Nouveau partenaire')

@section('styles')
    <script src="{{ asset_v('js/image-upload.js') }}?v={{ filemtime(public_path('js/image-upload.js')) }}" defer></script>
@endsection

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Ajouter un partenaire</h1>
            <p class="admDash__sub">Logo, description et lien facultatif vers le site du partenaire.</p>
        </div>
        <x-adm-back :href="route('admin.partenaires.index')" />
    </div>

    <div class="admPanel admPanel--full">
        <div class="admPanel__body">
            <form method="POST" action="{{ route('admin.partenaires.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.partenaires._form', ['partenaire' => null])

                <div style="margin-top:30px; display:flex; justify-content:flex-end; gap:12px;">
                    <button type="submit" class="btn" style="background:#22c55e; color:#fff; border-radius:12px; padding:12px 30px; font-weight:800; border:none; cursor:pointer;">Enregistrer</button>
                    <a href="{{ route('admin.partenaires.index') }}" style="color:#64748b; text-decoration:none; font-weight:600; align-self:center;">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
