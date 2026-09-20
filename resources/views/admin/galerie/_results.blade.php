{{-- Grille de photos --}}
@if($photos->count() === 0)
    <div class="admPanel admPanel--full" style="text-align:center; padding:50px;">
        <p style="color:#64748b;">Aucune photo ne correspond à vos critères.</p>
    </div>
@else
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
        @foreach($photos as $p)
            <div class="admPanel" style="grid-column: span 1; transition: transform 0.3s ease;">
                {{-- Thumbnail --}}
                <div style="position:relative; height:180px; overflow:hidden;">
                    <img src="{{ $p->image_url }}" alt="Photo" style="width:100%; height:100%; object-fit:cover;">
                    <div style="position:absolute; top:10px; left:10px;">
                        <span style="background: {{ $p->is_published ? 'rgba(34,197,94,0.8)' : 'rgba(239,68,68,0.8)' }}; color:#fff; padding:4px 10px; border-radius:20px; font-size:10px; font-weight:900; backdrop-filter:blur(5px); text-transform:uppercase;">
                            {{ $p->is_published ? 'Publié' : 'Privé' }}
                        </span>
                    </div>
                </div>

                {{-- Meta --}}
                <div class="admPanel__body" style="padding:15px;">
                    <div style="font-weight:800; color:#fff; font-size:14px; margin-bottom:5px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $p->title ?: 'Sans titre' }}
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                        <span style="color:#4ade80; font-size:11px; font-weight:700; text-transform:uppercase;">{{ $p->category }}</span>
                        <span style="color:#64748b; font-size:11px;">{{ optional($p->event_date)->format('d/m/Y') }}</span>
                    </div>

                    {{-- Actions --}}
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px;">
                        <form method="POST" action="{{ route('admin.galerie.toggle', $p) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="admQuick__btn" style="width:100%; font-size:11px;">
                                {{ $p->is_published ? 'Dépublier' : 'Publier' }}
                            </button>
                        </form>
                        <a href="{{ route('admin.galerie.edit', $p) }}" class="admQuick__btn" style="text-decoration:none; text-align:center; font-size:11px; border-color:rgba(59,130,246,0.3); color:#60a5fa;">Modifier</a>

                        <form method="POST" action="{{ route('admin.galerie.destroy', $p) }}" style="grid-column: span 2;" onsubmit="return confirm('Supprimer définitivement ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="admQuick__btn" style="width:100%; font-size:11px; color:#f87171; border-color:rgba(239,68,68,0.2);">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}

<div class="custom-pagination">
    {{ $photos->links('vendor.pagination.admin') }}
</div>
@endif
