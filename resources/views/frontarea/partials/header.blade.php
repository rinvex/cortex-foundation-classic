<!-- This example requires Tailwind CSS v2.0+ -->
<header>
    <div class="relative bg-white">
        <div class="flex justify-between items-center px-4 py-6 sm:px-6 md:justify-start md:space-x-10">
            <div>
                <a class="flex" href="{{ route('frontarea.home') }}">
                    <span class="sr-only">{{ config('app.name') }}</span>
                    <b>{{ config('app.name') }}</b>
                </a>
            </div>
            <div class="-mr-2 -my-2 md:hidden">
                <button type="button" class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-expanded="false">
                    <span class="sr-only">Open menu</span>
                    <!-- Heroicon name: outline/menu -->
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
            <div class="hidden md:flex-1 md:flex md:items-center md:justify-between">
                <nav class="flex space-x-10">
                    {!! Menu::render('frontarea.header.navigation') !!}
                </nav>
                <div class="flex items-center md:ml-12">
                    {!! Menu::render('frontarea.header.language') !!}
                    {!! Menu::render('frontarea.header.user') !!}
                </div>
            </div>
        </div>
    </div>
</header>
