<div style="padding-top: 5px;">
    @if($model->exists)
        <small>
            <strong>{{ trans('cortex/foundation::common.id') }}:</strong> {{ $model->getRouteKey() }}
        </small>

        @if($model->created_at || $model->updated_at) | @endif

        @if($model->created_at)
            <small>
                <strong>{{ trans('cortex/foundation::common.created_at') }}:</strong>
                <time datetime="{{ $model->created_at }}">{{ $model->created_at->format(config('app.date_format')) }}</time>
                @if($model->createdBy && app('request.user')->can('update', $model->createdBy)) {{ trans('cortex/foundation::common.by') }} <a href="{{ route(request()->accessarea().'.cortex.auth.'.Str::plural($model->updatedBy->getMorphClass()).'.edit', [$model->updatedBy->getMorphClass() => $model->createdBy]) }}">{{ $model->createdBy->username }}</a> @endif
            </small>
        @endif

        @if($model->created_at && $model->updated_at) | @endif

        @if($model->updated_at)
            <small>
                <strong>{{ trans('cortex/foundation::common.updated_at') }}:</strong>
                <time datetime="{{ $model->updated_at }}">{{ $model->updated_at->format(config('app.date_format')) }}</time>
                @if($model->updatedBy && app('request.user')->can('update', $model->updatedBy)) {{ trans('cortex/foundation::common.by') }} <a href="{{ route(request()->accessarea().'.cortex.auth.'.Str::plural($model->updatedBy->getMorphClass()).'.edit', [$model->updatedBy->getMorphClass() => $model->updatedBy]) }}">{{ $model->updatedBy->username }}</a> @endif
            </small>
        @endif
    @endif
</div>
