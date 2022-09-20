<div class="row">
    <div class="drag-container"></div>
    <div class="col-md-2" style="padding-right: 0">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <span>{{ trans('cortex/foundation::common.available_components') }}</span>
            </div>
            <div class="panel-body" style="padding: 0">
                <div class="grid-2 columns drag-enabled">
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-3">
                {{ Form::text('search', '', [ 'class' => 'grid-control-field form-control search-field', 'placeholder' => trans('cortex/foundation::common.search')]) }}
            </div>
            <div class="col-md-3">
                {{ Form::select('sort', $dragOptions, 'drag', [ 'class' => 'grid-control-field form-control sort-field select2', 'placeholder' => trans ('cortex/foundation::common.sort') ]) }}
            </div>
            <div class="col-md-3">
                {{ Form::select('filter', $colorOptions, 'all', [ 'class' => 'form-control grid-control-field filter-field select2', 'placeholder' => trans ('cortex/foundation::common.filter') ]) }}
            </div>
            <div class="col-md-3">
                {{ Form::select('layout', $positionOptions, 'left-top', [ 'class' => 'form-control layout-field grid-control-field select2', 'id' => 'layout-field', 'placeholder' => trans ('cortex/foundation::common.layout') ]) }}
            </div>
        </div>
        <div class="grid drag-enabled">
        </div>
    </div>
    <div id="grid-items" style="display: none">
        {{ $slot }}
    </div>
</div>


@push('styles')
    <link href="{{ mix('css/muuri.css') }}" rel="stylesheet">
@endpush

@push('vendor-scripts')
    <script src="{{ mix('js/muuri.js') }}" defer></script>
    <script src="{{ mix('js/web-animations.min.js') }}" defer></script>
@endpush

@push('inline-scripts')
    <script src="{{ mix('js/adjustable-layout.js') }}" defer></script>
@endpush

