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
        <div class="grid drag-enabled">
        </div>
    </div>
    <div id="grid-items" style="display: none">
        {{ $slot }}
    </div>
</div>


@push('styles')
    <link href="{{ mix('css/muuri.css') }}" rel="stylesheet">
    <style>
        .narrow-layout {
            display: none;
        }
    </style>
@endpush

@push('vendor-scripts')
    <script src="{{ mix('js/muuri.js') }}" defer></script>
    <script src="{{ mix('js/web-animations.min.js') }}" defer></script>
@endpush

@push('inline-scripts')
    <script src="{{ mix('js/adjustable-layout.js') }}" defer></script>
@endpush

