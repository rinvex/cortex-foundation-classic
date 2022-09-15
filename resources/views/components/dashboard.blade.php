<div class="drag-container"></div>
<div class="grid drag-enabled">
    {{ $slot }}
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

