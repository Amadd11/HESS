@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-1 select-none flex-nowrap shrink-0 text-xs">
        {{-- Tombol Sebelumnya --}}
        @if ($paginator->onFirstPage())
            <span class="h-8 px-2.5 inline-flex items-center gap-1 font-medium text-gray-300 bg-gray-50 border border-gray-200/60 rounded-lg cursor-not-allowed select-none">
                &laquo; <span class="hidden sm:inline font-semibold">Sebelumnya</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}"
               class="h-8 px-2.5 inline-flex items-center gap-1 font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-primary-50 hover:text-primary-700 hover:border-primary-200 shadow-2xs hover:shadow-xs transition select-none">
                &laquo; <span class="hidden sm:inline">Sebelumnya</span>
            </a>
        @endif

        {{-- Deretan Nomor Halaman --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="w-6 h-8 flex items-center justify-center text-gray-400 font-bold select-none">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page"
                              class="min-w-[32px] h-8 px-2 flex items-center justify-center font-black text-white bg-primary-700 border border-primary-700 rounded-lg shadow-xs ring-2 ring-primary-100 select-none">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                           class="min-w-[32px] h-8 px-2 flex items-center justify-center font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-primary-50 hover:text-primary-700 hover:border-primary-200 shadow-2xs hover:shadow-xs transition select-none">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Tombol Berikutnya --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}"
               class="h-8 px-2.5 inline-flex items-center gap-1 font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-primary-50 hover:text-primary-700 hover:border-primary-200 shadow-2xs hover:shadow-xs transition select-none">
                <span class="hidden sm:inline">Berikutnya</span> &raquo;
            </a>
        @else
            <span class="h-8 px-2.5 inline-flex items-center gap-1 font-medium text-gray-300 bg-gray-50 border border-gray-200/60 rounded-lg cursor-not-allowed select-none">
                <span class="hidden sm:inline font-semibold">Berikutnya</span> &raquo;
            </span>
        @endif
    </nav>
@endif
