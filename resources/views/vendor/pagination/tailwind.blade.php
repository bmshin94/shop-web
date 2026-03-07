@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center space-x-1">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="flex items-center justify-center px-3 py-2 text-[11px] font-bold text-gray-400 bg-gray-50 border border-gray-100 rounded-xl cursor-not-allowed">
                <span class="material-symbols-outlined text-[16px]">chevron_left</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="flex items-center justify-center px-3 py-2 text-[11px] font-bold text-text-muted bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:text-primary transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[16px]">chevron_left</span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="flex items-center justify-center px-3 py-2 text-[11px] font-bold text-gray-400 bg-white rounded-xl cursor-default">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="flex items-center justify-center px-4 py-2 text-[11px] font-bold text-white bg-primary border border-primary rounded-xl shadow-md shadow-primary/20 cursor-default transform scale-105 transition-transform">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="flex items-center justify-center px-4 py-2 text-[11px] font-bold text-text-muted bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:text-primary transition-all shadow-sm">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="flex items-center justify-center px-3 py-2 text-[11px] font-bold text-text-muted bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:text-primary transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            </a>
        @else
            <span class="flex items-center justify-center px-3 py-2 text-[11px] font-bold text-gray-400 bg-gray-50 border border-gray-100 rounded-xl cursor-not-allowed">
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            </span>
        @endif
    </nav>
@endif
