<!doctype html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', config('app.name'))</title>

    <!-- Meta Data -->
    @include('cortex/foundation::frontend.partials.meta')

    <!-- Styles -->
    <link href="{{ mix('css/vendor.css') }}" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @stack('styles')

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token()]); ?>
    </script>
</head>
<body class="skin-blue-light layout-top-nav">
    <!-- Main Content -->
    <div class="wrapper">
        @include('cortex/foundation::frontend.partials.header')
        @yield('content')
        @include('cortex/foundation::frontend.partials.footer')
    </div>

    <!-- JavaScripts -->
    <script src="{{ mix('js/manifest.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/vendor.js') }}" type="text/javascript"></script>
    <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
    @stack('scripts')

    <!-- Alerts -->
    @alerts('default')
</body>
</html>
