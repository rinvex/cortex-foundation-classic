<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', config('app.name'))</title>

    {{-- Meta Data --}}
    @include('cortex/foundation::common.partials.meta')
    @stack('head-elements')

    {{-- Styles --}}
    <link href="{{ mix('css/vendor.css') }}" rel="stylesheet">
    <link href="{{ mix('css/theme-adminarea.css') }}" rel="stylesheet">
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
<body class="hold-transition skin-blue fixed sidebar-mini">

    {{-- Main content --}}
    <div id="app" class="wrapper">

        @include('cortex/foundation::adminarea.partials.header')
        @include('cortex/foundation::adminarea.partials.sidebar')

        @yield('content')

        @include('cortex/foundation::adminarea.partials.footer')

    </div>

    {{-- Scripts --}}
    @stack('inline-scripts')

    {{-- Alerts --}}
    @alerts('default')
</body>
</html>
