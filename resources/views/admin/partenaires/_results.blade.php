@if($partenaires->count() === 0)
    <div class="admPanel admPanel--full" style="text-align:center; padding:50px;">
        <p style="color:#64748b;">{{ $q !== '' || $slug !== '' ? 'Aucun partenaire ne correspond au filtre.' : 'Aucun partenaire pour le moment.' }}</p>
    </div>
@else
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
        @foreach($partenaires as $p)
            <div class="admPanel" style="grid-column: span 1;">
                <div style="position:relative; height:150px; background:#fff; display:flex; align-items:center; justify-content:center; padding:18px;">
                    <img src="{{ $p->logo_url }}" alt="{{ $p->nom }}" style="max-width:100%; max-height:100%; object-fit:contain;">
                    <div style="position:absolute; top:10px; left:10px;">
                        <span style="background: {{ $p->is_published ? 'rgba(34,197,94,0.9)' : 'rgba(239,68,68,0.9)' }}; color:#fff; padding:4px 10px; border-radius:20px; font-size:10px; font-weight:900; text-transform:uppercase;">
                            {{ $p->is_published ? 'Publié' : 'Masqué' }}
                        </span>
                    </div>
                </div>

                <div class="admPanel__body" style="padding:15px;">
                    <div style="font-weight:800; color:#fff; font-size:14px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->nom }}</div>
                    <div style="font-size:11px; color:#4ade80; font-weight:700; text-transform:uppercase; margin:4px 0 14px;">{{ $p->categorie?->nom ?? 'Non classé' }}</div>

                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px;">
                        <form method="POST" action="{{ route('admin.partenaires.toggle', $p) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="admQuick__btn" style="width:100%; font-size:11px;">{{ $p->is_published ? 'Masquer' : 'Publier' }}</button>
                        </form>
                        <a href="{{ route('admin.partenaires.edit', $p) }}" class="admQuick__btn" style="text-decoration:none; text-align:center; font-size:11px; border-color:rgba(59,130,246,0.3); color:#60a5fa;">Modifier</a>
                        <form method="POST" action="{{ route('admin.partenaires.destroy', $p) }}" style="grid-column: span 2;" onsubmit="return confirm('Supprimer « {{ $p->nom }} » ?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="admQuick__btn" style="width:100%; font-size:11px; color:#f87171; border-color:rgba(239,68,68,0.2);">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="custom-pagination">
        {{ $partenaires->links('vendor.pagination.admin') }}
    </div>
@endif
