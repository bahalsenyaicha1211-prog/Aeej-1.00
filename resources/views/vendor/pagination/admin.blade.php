@if ($paginator->hasPages())
    <nav class="admPag" role="navigation" aria-label="{{ __('Pagination Navigation') }}">
        <p class="admPag__info">
            {!! __('Showing') !!}
            @if ($paginator->firstItem())
                <strong>{{ $paginator->firstItem() }}</strong> {!! __('to') !!} <strong>{{ $paginator->lastItem() }}</strong>
            @else
                {{ $paginator->count() }}
            @endif
            {!! __('of') !!} <strong>{{ $paginator->total() }}</strong> {!! __('results') !!}
        </p>

        <ul class="admPag__list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li><span class="admPag__btn admPag__btn--disabled" aria-disabled="true">&laquo;</span></li>
            @else
                <li><a class="admPag__btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}">&laquo;</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="admPag__dots">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span class="admPag__btn admPag__btn--current" aria-current="page">{{ $page }}</span></li>
                        @else
                            <li><a class="admPag__btn" href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a class="admPag__btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}">&raquo;</a></li>
            @else
                <li><span class="admPag__btn admPag__btn--disabled" aria-disabled="true">&raquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
