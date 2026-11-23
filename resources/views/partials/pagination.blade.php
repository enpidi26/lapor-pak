@if ($paginator->hasPages())
    <nav class="pager" aria-label="Halaman">
        <p class="pager__note">
            {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }}
        </p>

        <div class="pager__links">
            @if ($paginator->onFirstPage())
                <span class="pager__link is-off" aria-hidden="true"><x-icon name="chevron-left" :size="16" /></span>
            @else
                <a class="pager__link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya"><x-icon name="chevron-left" :size="16" /></a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pager__link">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pager__current" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="pager__link" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="pager__link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya"><x-icon name="chevron-right" :size="16" /></a>
            @else
                <span class="pager__link is-off" aria-hidden="true"><x-icon name="chevron-right" :size="16" /></span>
            @endif
        </div>
    </nav>
@endif
