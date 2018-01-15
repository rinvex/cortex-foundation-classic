<div class="modal fade" id="delete-confirmation" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmation" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                {{ Form::button('<span aria-hidden="true">&times;</span>', ['class' => 'close', 'data-dismiss' => 'modal', 'aria-label' => 'Close', 'title' => trans('cortex/foundation::common.close')]) }}
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                {{ Form::button(trans('cortex/foundation::common.cancel'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) }}
                <a href="#" class="btn btn-danger" data-form="delete" data-token="{{ csrf_token() }}"><i class="fa fa-trash-o"></i> {{ trans('cortex/foundation::common.delete') }}</a>
            </div>
        </div>
    </div>
</div>
