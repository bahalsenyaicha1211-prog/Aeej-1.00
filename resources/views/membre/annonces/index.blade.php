<x-member-layout :unreadAnnoncesCount="$unreadAnnoncesCount ?? 0">
    <x-slot name="header">Annonces</x-slot>

    <div class="container" style="padding:0;">
        <div class="card">
            <div class="section__head">
                <div class="section__title">Toutes les annonces</div>
                <div class="section__link">{{ $annonces->total() }} au total</div>
            </div>

            <div class="list">
                @forelse($annonces as $annonce)
                    <a class="item" href="{{ route('membre.annonces.show', $annonce) }}">
                        <div class="meta">
                            <div style="display:flex; align-items:center; gap:8px;">
                                @if($annonce->is_pinned)
                                    <span class="tag">Épinglée</span>
                                @endif
                            </div>
                            <div>
                                {{ optional($annonce->published_at ?? $annonce->created_at)->format('d/m/Y') }}
                            </div>
                        </div>

                        <div style="font-weight:900;">
                            {{ \Illuminate\Support\Str::limit($annonce->contenu, 130) }}
                        </div>

                        @if($annonce->image_url)
                            <div style="margin-top:10px;">
                                <img src="{{ $annonce->image_url }}" alt="Image annonce"
                                     style="border-radius:14px; border:1px solid var(--border); max-height:260px; width:100%; object-fit:cover;">
                            </div>
                        @endif
                    </a>
                @empty
                    <div class="item" style="color:var(--muted); text-align:center;">Aucune annonce disponible.</div>
                @endforelse
            </div>

            <div style="margin-top:14px;">
                {{ $annonces->links() }}
            </div>
        </div>
    </div>
</x-member-layout>
