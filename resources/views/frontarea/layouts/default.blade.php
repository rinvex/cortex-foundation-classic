<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', config('app.name'))</title>

    {{-- Meta Data --}}
    @include('cortex/foundation::common.partials.meta')
    @stack('head-elements')

    {{-- Fonts --}}
    <link href='https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;1,300;1,400&display=swap' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;700&display=swap' rel='stylesheet' type='text/css'>

    {{-- Styles --}}
    <link href="{{ mix('css/vendor.css') }}" rel="stylesheet">
    <link href="{{ mix('css/theme-frontarea.css') }}" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @stack('styles')

    {{-- Scripts --}}
    <script>
        window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token()]); ?>;
        window.Accessarea = "<?php echo request()->accessarea(); ?>";
    </script>
    <script src="{{ mix('js/manifest.js') }}" defer></script>
    <script src="{{ mix('js/vendor.js') }}" defer></script>
    @stack('vendor-scripts')
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body @yield('body-attributes')>

    <div id="app">

        @include('cortex/foundation::frontarea.partials.header')

        @yield('content')

        @include('cortex/foundation::frontarea.partials.footer')

    </div>

    {{-- Scripts --}}
    @stack('inline-scripts')

    {{-- Alerts --}}
    @alerts('default')

</body>
</html>
