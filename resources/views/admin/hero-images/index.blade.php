@extends('layouts.admin')

@section('title', 'Admin • Diaporama accueil')
@section('header', 'Diaporama de la page d’accueil')

@section('content')
<div class="admDash">
    {{-- Header --}}
    <div class="admDash__head">
        <div>
            <h1 class="admDash__title text-white">Photos de la page d'accueil</h1>
            <p class="admDash__sub">Ajoutez ou retirez les images qui défilent en haut de la page d'accueil.</p>
        </div>
        <a class="btn" style="background: #22c55e; color: #fff; border-radius: 12px; padding: 10px 20px; font-weight: 800; text-decoration: none;" href="{{ route('admin.hero-images.create') }}">
            + Ajouter des photos
        </a>
    </div>

    @if($images->count() === 0)
        <div class="admPanel admPanel--full" style="text-align:center; padding:50px;">
            <p style="color:#64748b;">Aucune photo dans le diaporama. La page d'accueil affiche un visuel par défaut en attendant.</p>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
            @foreach($images as $img)
                <div class="admPanel" style="grid-column: span 1;">
                    {{-- Thumbnail --}}
                    <div style="position:relative; height:180px; overflow:hidden;">
                        <img src="{{ $img->image_url }}" alt="{{ $img->alt }}" style="width:100%; height:100%; object-fit:cover;">
                        <div style="position:absolute; top:10px; left:10px;">
                            <span style="background: {{ $img->is_active ? 'rgba(34,197,94,0.8)' : 'rgba(239,68,68,0.8)' }}; color:#fff; padding:4px 10px; border-radius:20px; font-size:10px; font-weight:900; backdrop-filter:blur(5px); text-transform:uppercase;">
                                {{ $img->is_active ? 'Affichée' : 'Masquée' }}
                            </span>
                        </div>
                        <div style="position:absolute; top:10px; right:10px;">
                            <span style="background: rgba(0,0,0,0.55); color:#fff; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:800; backdrop-filter:blur(5px);">
                                #{{ $img->position }}
                            </span>
                        </div>
                    </div>

                    {{-- Meta / Actions --}}
                    <div class="admPanel__body" style="padding:15px;">
                        <div style="font-weight:700; color:#94a3b8; font-size:12px; margin-bottom:15px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $img->alt ?: 'Sans texte alternatif' }}
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px;">
                            <form method="POST" action="{{ route('admin.hero-images.toggle', $img) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="admQuick__btn" style="width:100%; font-size:11px;">
                                    {{ $img->is_active ? 'Masquer' : 'Afficher' }}
                                </button>
                            </form>
                            <a href="{{ route('admin.hero-images.edit', $img) }}" class="admQuick__btn" style="text-decoration:none; text-align:center; font-size:11px; border-color:rgba(59,130,246,0.3); color:#60a5fa;">Modifier</a>

                            <form method="POST" action="{{ route('admin.hero-images.destroy', $img) }}" style="grid-column: span 2;" onsubmit="return confirm('Retirer cette photo du diaporama ?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="admQuick__btn" style="width:100%; font-size:11px; color:#f87171; border-color:rgba(239,68,68,0.2);">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="custom-pagination">
            {{ $images->links('vendor.pagination.admin') }}
        </div>
    @endif
</div>
@endsection
