<x-member-layout :unreadAnnoncesCount="$unreadAnnoncesCount ?? 0">
    <x-slot name="header">Annonce</x-slot>

    <div class="container" style="padding:0;">
        <div class="card">
            <div class="meta" style="margin-bottom:10px;">
                <div style="display:flex; align-items:center; gap:8px;">
                    @if($annonce->is_pinned)
                        <span class="tag">Épinglée</span>
                    @endif
                </div>

                <div>
                    {{ $annonce->published_at?->format('d/m/Y à H:i') ?? $annonce->created_at->format('d/m/Y à H:i') }}
                </div>
            </div>

            <div class="text">
                {{ $annonce->contenu }}
            </div>

            @if($annonce->image_url)
                <div style="margin-top:14px;">
                    <img src="{{ $annonce->image_url }}" alt="Image annonce"
                         style="border-radius:14px; border:1px solid var(--border); max-height:520px; width:100%; object-fit:cover;">
                </div>
            @endif

            <div style="margin-top:16px; display:flex; gap:10px; justify-content:flex-start;">
                <a class="btn btn--ghost" href="{{ route('membre.annonces.index') }}">← Retour</a>
            </div>
        </div>
    </div>
</x-member-layout>
