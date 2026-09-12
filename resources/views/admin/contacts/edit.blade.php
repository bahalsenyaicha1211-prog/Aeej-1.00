@extends('layouts.admin')

@section('title', 'Admin • Modifier un contact')
@section('header', 'Édition contact')

@section('styles')
    <script src="{{ asset_v('js/image-upload.js') }}" defer></script>
@endsection

@section('content')
<div class="admDash">
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Modifier le contact</h1>
            <p class="admDash__sub">{{ $personne->nom }} — {{ $personne->poste }}</p>
        </div>
        <x-adm-back :href="route('admin.contacts.index')" />
    </div>

    <div class="admPanel admPanel--full">
        <div class="admPanel__body">
            <form method="POST" action="{{ route('admin.contacts.update', $personne) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                @include('admin.contacts._form')

                <div style="margin-top:24px; display:flex; gap:12px;">
                    <button type="submit" class="btn" style="background:#3b82f6; color:#fff; border-radius:12px; padding:12px 30px; font-weight:800; border:none; cursor:pointer;">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
