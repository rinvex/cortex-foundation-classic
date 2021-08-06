{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@push('inline-scripts')
    {!! JsValidator::formRequest(Cortex\Foundation\Http\Requests\Adminarea\AccessareaFormRequest::class)->selector("#adminarea-cortex-foundation-accessareas-create-form, #adminarea-cortex-foundation-accessareas-{$accessarea->getRouteKey()}-update-form")->ignore('.skip-validation') !!}
@endpush

{{-- Main Content --}}
@section('content')

    @includeWhen($accessarea->exists, 'cortex/foundation::adminarea.partials.modal', ['id' => 'delete-confirmation'])

    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ Breadcrumbs::render() }}</h1>
        </section>

        {{-- Main content --}}
        <section class="content">

            <div class="nav-tabs-custom">
                @includeWhen($accessarea->exists, 'cortex/foundation::adminarea.partials.actions', ['name' => 'accessarea', 'model' => $accessarea, 'resource' => trans('cortex/foundation::common.accessarea'), 'routePrefix' => 'adminarea.cortex.foundation.accessareas.'])
                {!! Menu::render('adminarea.cortex.foundation.accessareas.tabs', 'nav-tab') !!}

                <div class="tab-content">

                    <div class="tab-pane active" id="details-tab">

                        @if ($accessarea->exists)
                            {{ Form::model($accessarea, ['url' => route('adminarea.cortex.foundation.accessareas.update', ['accessarea' => $accessarea]), 'method' => 'put', 'id' => "adminarea-cortex-foundation-accessareas-{$accessarea->getRouteKey()}-update-form", 'files' => true]) }}
                        @else
                            {{ Form::model($accessarea, ['url' => route('adminarea.cortex.foundation.accessareas.store'), 'id' => 'adminarea-cortex-foundation-accessareas-create-form', 'files' => true]) }}
                        @endif

                            <div class="row">

                                <div class="col-md-6">

                                    {{-- Name --}}
                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        {{ Form::label('name', trans('cortex/foundation::common.name'), ['class' => 'control-label']) }}
                                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('cortex/foundation::common.name'), 'data-slugify' => '[name="slug"]', 'required' => 'required', 'autofocus' => 'autofocus']) }}

                                        @if ($errors->has('name'))
                                            <span class="help-block">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-3">

                                    {{-- Slug --}}
                                    <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                                        {{ Form::label('slug', trans('cortex/foundation::common.slug'), ['class' => 'control-label']) }}
                                        {{ Form::text('slug', null, ['class' => 'form-control', 'placeholder' => trans('cortex/foundation::common.slug'), 'required' => 'required']) }}

                                        @if ($errors->has('slug'))
                                            <span class="help-block">{{ $errors->first('slug') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-3">

                                    {{-- Prefix --}}
                                    <div class="form-group{{ $errors->has('prefix') ? ' has-error' : '' }}">
                                        {{ Form::label('prefix', trans('cortex/foundation::common.prefix'), ['class' => 'control-label']) }}
                                        {{ Form::text('prefix', null, ['class' => 'form-control', 'placeholder' => trans('cortex/foundation::common.prefix')]) }}

                                        @if ($errors->has('prefix'))
                                            <span class="help-block">{{ $errors->first('prefix') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>


                            <div class="row">

                                <div class="col-md-6">

                                    {{-- Is Active --}}
                                    <div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
                                        {{ Form::label('is_active', trans('cortex/foundation::common.is_active'), ['class' => 'control-label']) }}
                                        {{ Form::select('is_active', [1 => trans('cortex/foundation::common.yes'), 0 => trans('cortex/foundation::common.no')], null, ['class' => 'form-control select2', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%', 'required' => 'required']) }}

                                        @if ($errors->has('is_active'))
                                            <span class="help-block">{{ $errors->first('is_active') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-6">

                                    {{-- Is Protected --}}
                                    <div class="form-group{{ $errors->has('is_protected') ? ' has-error' : '' }}">
                                        {{ Form::label('is_protected', trans('cortex/foundation::common.is_protected'), ['class' => 'control-label']) }}
                                        {{ Form::select('is_protected', [1 => trans('cortex/foundation::common.yes'), 0 => trans('cortex/foundation::common.no')], null, ['class' => 'form-control select2', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%', 'required' => 'required']) }}

                                        @if ($errors->has('is_protected'))
                                            <span class="help-block">{{ $errors->first('is_protected') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-4">

                                    {{-- Is Indexable --}}
                                    <div class="form-group{{ $errors->has('is_indexable') ? ' has-error' : '' }}">
                                        {{ Form::label('is_indexable', trans('cortex/foundation::common.is_indexable'), ['class' => 'control-label']) }}
                                        {{ Form::select('is_indexable', [1 => trans('cortex/foundation::common.yes'), 0 => trans('cortex/foundation::common.no')], null, ['class' => 'form-control select2', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%', 'required' => 'required']) }}

                                        @if ($errors->has('is_indexable'))
                                            <span class="help-block">{{ $errors->first('is_indexable') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Is Scoped --}}
                                    <div class="form-group{{ $errors->has('is_scoped') ? ' has-error' : '' }}">
                                        {{ Form::label('is_scoped', trans('cortex/foundation::common.is_scoped'), ['class' => 'control-label']) }}
                                        {{ Form::select('is_scoped', [1 => trans('cortex/foundation::common.yes'), 0 => trans('cortex/foundation::common.no')], null, ['class' => 'form-control select2', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%', 'required' => 'required']) }}

                                        @if ($errors->has('is_scoped'))
                                            <span class="help-block">{{ $errors->first('is_scoped') }}</span>
                                        @endif
                                    </div>

                                </div>

                                <div class="col-md-4">

                                    {{-- Is Obscured --}}
                                    <div class="form-group{{ $errors->has('is_obscured') ? ' has-error' : '' }}">
                                        {{ Form::label('is_obscured', trans('cortex/foundation::common.is_obscured'), ['class' => 'control-label']) }}
                                        {{ Form::select('is_obscured', [1 => trans('cortex/foundation::common.yes'), 0 => trans('cortex/foundation::common.no')], null, ['class' => 'form-control select2', 'data-minimum-results-for-search' => 'Infinity', 'data-width' => '100%', 'required' => 'required']) }}

                                        @if ($errors->has('is_obscured'))
                                            <span class="help-block">{{ $errors->first('is_obscured') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>


                            <div class="row">

                                <div class="col-md-12">

                                    {{-- Description --}}
                                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                        {{ Form::label('description', trans('cortex/foundation::common.description'), ['class' => 'control-label']) }}
                                        {{ Form::textarea('description', null, ['class' => 'form-control tinymce', 'placeholder' => trans('cortex/foundation::common.description'), 'rows' => 3]) }}

                                        @if ($errors->has('description'))
                                            <span class="help-block">{{ $errors->first('description') }}</span>
                                        @endif
                                    </div>

                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12">

                                    <div class="pull-right">
                                        {{ Form::button(trans('cortex/foundation::common.submit'), ['class' => 'btn btn-primary btn-flat', 'type' => 'submit']) }}
                                    </div>

                                    @include('cortex/foundation::adminarea.partials.timestamps', ['model' => $accessarea])

                                </div>

                            </div>

                        {{ Form::close() }}

                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection
