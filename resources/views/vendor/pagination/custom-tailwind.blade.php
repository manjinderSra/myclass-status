@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center items-center gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1.5 text-gray-400 bg-gray-100 border border-gray-200 rounded cursor-not-allowed">
                ← Prev
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1.5 bg-white border border-gray-200 rounded hover:bg-blue-50 text-blue-600">
                ← Prev
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-3 py-1 text-gray-400">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-1.5 bg-blue-600 text-white border border-blue-600 rounded">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1.5 bg-white border border-gray-200 rounded hover:bg-blue-50 text-blue-600">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1.5 bg-white border border-gray-200 rounded hover:bg-blue-50 text-blue-600">
                Next →
            </a>
        @else
            <span class="px-3 py-1.5 text-gray-400 bg-gray-100 border border-gray-200 rounded cursor-not-allowed">
                Next →
            </span>
        @endif
    </nav>
@endif
