{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

{{-- Main Content --}}
@section('content')

    <div>
        <section class="pt-3">
            <h1>{{ Breadcrumbs::render() }}</h1>
        </section>
        {{-- Main content --}}
        <div class="bg-white p-3 my-3 rounded-sm shadow-md border border-gray-100">
            @yield('datatable-filters')
            {!! $dataTable->pusher($pusher ?? null)->routePrefix($routePrefix ?? null)->table(['id' => $id]) !!}
        </div>
    </div>

@endsection

@push('head-elements')
    <meta name="turbolinks-cache-control" content="no-cache">
@endpush

@push('styles')
    <link href="{{ mix('css/datatables.css') }}" rel="stylesheet">
@endpush

@push('vendor-scripts')
    <script src="{{ mix('js/datatables.js') }}" defer></script>
@endpush

@push('inline-scripts')
    {!! $dataTable->scripts() !!}
@endpush
