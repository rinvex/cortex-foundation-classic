{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/foundation::common.adminarea') }} » {{ $phrase }} » {{ $resource->username ?? $resource->name ?? $resource->slug ?? '' }}
@stop

{{-- Main Content --}}
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>@yield('name', $resource->username ?? $resource->name ?? $resource->slug ?? '')</h1>
            <!-- Breadcrumbs -->
            {{ Breadcrumbs::render() }}
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    @section('tabs')
                        <li><a href="{{ route("adminarea.{$type}.edit", [str_singular($type) => $resource]) }}">{{ trans('cortex/foundation::common.details') }}</a></li>
                        <li class="active"><a href="#{{ $tab ?? 'logs' }}-tab" data-toggle="tab">{{ trans('cortex/foundation::common.logs') }}</a></li>
                        @if(method_exists($resource, 'causedActivity')) <li><a href="{{ route("adminarea.{$type}.activities", [str_singular($type) => $resource]) }}">{{ trans('cortex/foundation::common.activities') }}</a></li> @endif
                        <li style="float: right; padding: 5px"><select class="form-control dataTableBuilderLengthChanger" aria-controls="{{ $id }}-table"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select></li>
                    @show
                </ul>

                <div class="tab-content">

                    <div class="tab-pane active" id="{{ $tab ?? 'logs' }}-tab">

                        {!! $dataTable->table(['class' => 'table table-striped responsive dataTableBuilder', 'id' => "{$id}"]) !!}

                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush

