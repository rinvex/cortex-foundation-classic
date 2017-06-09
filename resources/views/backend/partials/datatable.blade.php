{{-- Master Layout --}}
@extends('cortex/foundation::backend.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/foundation::common.backend') }} » {{ trans($resource) }}
@stop

{{-- Main Content --}}
@section('content')

    <div class="content-wrapper">
        <!-- Breadcrumbs -->
        <section class="content-header">
            <h1>{{ trans($resource) }}</h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('backend.home') }}"><i class="fa fa-dashboard"></i> {{ trans('cortex/foundation::common.backend') }}</a></li>
                <li class="active">{{ trans($resource) }}</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">
                                {{ trans($resource) }}
                            </h3>
                            <div class="box-tools">
                                <select id="dataTableBuilderLengthChanger" aria-controls="{{ $resource }}-table" class="form-control"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            {!! $dataTable->table(['class' => 'table table-striped table-hover responsive']) !!}

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>

@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush
