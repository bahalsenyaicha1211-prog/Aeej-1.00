@if ($paginator->hasPages())
    <nav class="memPag" role="navigation" aria-label="{{ __('Pagination Navigation') }}" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; padding:10px 0;">
        <p style="margin:0; font-size:12.5px; color:var(--muted);">
            {!! __('Showing') !!}
            @if ($paginator->firstItem())
                <strong>{{ $paginator->firstItem() }}</strong> {!! __('to') !!} <strong>{{ $paginator->lastItem() }}</strong>
            @else
                {{ $paginator->count() }}
            @endif
            {!! __('of') !!} <strong>{{ $paginator->total() }}</strong> {!! __('results') !!}
        </p>

        <ul style="display:flex; align-items:center; gap:6px; flex-wrap:wrap; margin:0; padding:0; list-style:none;">
            @if ($paginator->onFirstPage())
                <li><span style="display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 10px; border-radius:8px; border:1px solid var(--border); color:var(--muted); opacity:0.6;">&laquo;</span></li>
            @else
                <li><a style="display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 10px; border-radius:8px; border:1px solid var(--border); color:var(--text); text-decoration:none; font-weight:700;" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span style="display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; color:var(--muted);">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span style="display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 10px; border-radius:8px; background:var(--brand); color:#fff; font-weight:800;">{{ $page }}</span></li>
                        @else
                            <li><a style="display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 10px; border-radius:8px; border:1px solid var(--border); color:var(--text); text-decoration:none; font-weight:700;" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a style="display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 10px; border-radius:8px; border:1px solid var(--border); color:var(--text); text-decoration:none; font-weight:700;" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
            @else
                <li><span style="display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 10px; border-radius:8px; border:1px solid var(--border); color:var(--muted); opacity:0.6;">&raquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
