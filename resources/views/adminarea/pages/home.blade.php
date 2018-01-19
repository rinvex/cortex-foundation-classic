{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/foundation::common.adminarea') }}
@endsection

{{-- Main Content --}}
@section('content')

    <div class="content-wrapper">

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12">
                    <h1><i class="fa fa-dashboard"></i> {{ trans('cortex/foundation::common.adminarea_welcome') }}</h1>
                    <h4>{{ trans('cortex/foundation::common.adminarea_welcome_body') }}</h4>
                </div>

            </div>
            <!-- /.row -->

        </section>
        <!-- /.content -->
    </div>

@endsection
