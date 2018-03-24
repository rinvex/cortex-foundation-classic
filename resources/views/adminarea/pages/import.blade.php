{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/foundation::common.adminarea') }} » {{ $phrase }} » {{ trans('cortex/foundation::common.import') }}
@endsection

{{-- Main Content --}}
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ Breadcrumbs::render() }}</h1>
        </section>

        {{-- Main content --}}
        <section class="content">

            <div class="nav-tabs-custom">
                {!! Menu::render("{$tabs}", 'nav-tab') !!}

                <div class="tab-content">

                    <div class="tab-pane active" id="{{ $id }}-tab">
                        {{ Form::open(['url' => $url, 'class' => 'dropzone', 'id' => "{$id}-dropzone", 'data-dz-accepted-files' => 'application/vnd.ms-excel']) }}
                            <div class="dz-message" data-dz-message><span>{{ trans('cortex/foundation::common.drop_to_import') }}</span></div>
                        {{ Form::close() }}
                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection
