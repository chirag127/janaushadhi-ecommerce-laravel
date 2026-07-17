@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="flex items-center justify-between">
    <div class="flex flex-1 justify-between sm:hidden">
        @if ($paginator->onFirstPage())
            <span class="btn-secondary cursor-default opacity-50">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="btn-secondary">Previous</a>
        @endif
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="btn-secondary">Next</a>
        @else
            <span class="btn-secondary cursor-default opacity-50">Next</span>
        @endif
    </div>

    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Showing <span class="font-medium">{{ $paginator->firstItem() }}</span> to <span class="font-medium">{{ $paginator->lastItem() }}</span> of <span class="font-medium">{{ $paginator->total() }}</span> results
        </p>
        <span class="inline-flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-300 dark:border-gray-700">&lsaquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800" aria-label="Previous">&lsaquo;</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 text-gray-400">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="flex h-9 min-w-9 items-center justify-center rounded-lg bg-brand-600 px-2 text-sm font-semibold text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="flex h-9 min-w-9 items-center justify-center rounded-lg border border-gray-200 px-2 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800" aria-label="Next">&rsaquo;</a>
            @else
                <span class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-300 dark:border-gray-700">&rsaquo;</span>
            @endif
        </span>
    </div>
</nav>
@endif
