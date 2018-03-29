@if (count($breadcrumbs))

    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)

            @if ($breadcrumb->url && !$loop->last)
                <li><a href="{{ $breadcrumb->url }}">{!! $breadcrumb->name !!}</a></li>
            @else
                <li class="active">{!! $breadcrumb->name !!}</li>
            @endif

        @endforeach
    </ol>

@endif
