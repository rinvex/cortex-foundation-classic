<div class="mb-4">
    <div>
        @if (count($breadcrumbs))
            <nav class="sm:hidden" aria-label="Back">
                <a href="{{ url()->previous() }}" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <!-- Heroicon name: solid/chevron-left -->
                    <svg class="flex-shrink-0 -ml-1 mr-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Back
                </a>
            </nav>
            <nav class="hidden sm:flex" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-4">
                    @foreach ($breadcrumbs as $breadcrumb)
                        @if ($breadcrumb->url && ! $loop->last)
                            <li>
                                <div class="flex">
                                    <a href="{{ $breadcrumb->url }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                                        {!! $breadcrumb->title !!}
                                    </a>
                                    <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </li>
                        @else
                            <li>
                                <a href="javascript:void(0)" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                                    {!! $breadcrumb->title !!}
                                </a>
                            </li>
                        @endif

                    @endforeach
                </ol>
            </nav>

        @endif
    </div>
    <div class="mt-2 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">{!! $breadcrumb->title !!}</h2>
        </div>
    </div>
</div>
