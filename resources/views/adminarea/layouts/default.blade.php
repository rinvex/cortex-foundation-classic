<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', config('app.name'))</title>

    <!-- Meta Data -->
    @include('cortex/foundation::common.partials.meta')

    <!-- Styles -->
    <link href="{{ mix('css/vendor.css', 'assets') }}" rel="stylesheet">
    <link href="{{ mix('css/theme-adminlte.css', 'assets') }}" rel="stylesheet">
    <link href="{{ mix('css/app.css', 'assets') }}" rel="stylesheet">
    @stack('styles')

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token()]); ?>;
        window.Accessarea = "<?php echo request('accessarea'); ?>";
    </script>
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
    <!-- Main Content -->
    <div class="wrapper">
        @include('cortex/foundation::adminarea.partials.header')
        @include('cortex/foundation::adminarea.partials.sidebar')

        @yield('content')

        @include('cortex/foundation::adminarea.partials.footer')
    </div>

    <!-- JavaScripts -->
    <script src="{{ mix('js/manifest.js', 'assets') }}" type="text/javascript"></script>
    <script src="{{ mix('js/vendor.js', 'assets') }}" type="text/javascript"></script>
    @stack('scripts-vendor')
        <script src="{{ mix('js/app.js', 'assets') }}" type="text/javascript"></script>
    @stack('scripts')

    <!-- Alerts -->
    @alerts('default')
</body>
</html>
