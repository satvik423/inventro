@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between mt-4">
        {{-- Mobile Navigation (Previous & Next Only) --}}
        <div class="flex justify-between w-full sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 text-sm font-medium text-gray-500 bg-gray-200 rounded-md cursor-not-allowed">
                    « Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    « Previous
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Next »
                </a>
            @else
                <span class="px-4 py-2 text-sm font-medium text-gray-500 bg-gray-200 rounded-md cursor-not-allowed">
                    Next »
                </span>
            @endif
        </div>

        {{-- Desktop Pagination --}}
        <div class="hidden sm:flex sm:items-center sm:justify-between w-full">
            <p class="text-sm text-gray-700">
                Showing 
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                to 
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                of 
                <span class="font-medium">{{ $paginator->total() }}</span> results
            </p>

            <div class="flex items-center space-x-2">
                {{-- Previous Page --}}
                {{-- @if ($paginator->onFirstPage())
                    <span class="px-3 py-2 text-gray-500 bg-gray-200 rounded-md cursor-not-allowed">«</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">«</a>
                @endif --}}

                {{-- Pagination Numbers --}}
                {{-- @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="px-3 py-2 text-gray-500 bg-gray-100 rounded-md">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="px-3 py-2 bg-blue-600 text-white rounded-md">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-2 text-gray-700 bg-white border rounded-md hover:bg-gray-200">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach --}}

                {{-- Next Page --}}
                {{-- @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">»</a>
                @else
                    <span class="px-3 py-2 text-gray-500 bg-gray-200 rounded-md cursor-not-allowed">»</span>
                @endif --}}
            </div>
        </div>
    </nav>
@endif
