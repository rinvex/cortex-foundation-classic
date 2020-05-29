<?php

declare(strict_types=1);

namespace Cortex\Foundation\DataTables;

use Cortex\Foundation\Models\ImportRecord;
use Cortex\Foundation\Transformers\ImportRecordTransformer;

/**
 * @property \Illuminate\Database\Eloquent\Model $resource
 * @property string                              $tabs
 * @property string                              $id
 * @property string                              $url
 */
class ImportRecordsDataTable extends AbstractDataTable
{
    /**
     * {@inheritdoc}
     */
    protected $model = ImportRecord::class;

    /**
     * {@inheritdoc}
     */
    protected $transformer = ImportRecordTransformer::class;

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        return parent::query()->where('resource', $this->resource->getMorphClass());
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return datatables($this->query())
            ->setTransformer(app($this->transformer))
            ->make(true);
    }

    /**
     * Get default builder parameters.
     *
     * @return array
     */
    protected function getBuilderParameters(): array
    {
        $columnsButton = ['extend' => 'colvis', 'text' => '<i class="fa fa-columns"></i> '.trans('cortex/foundation::common.columns').' <span class="caret"/>'];
        $lengthButton = ['extend' => 'pageLength', 'text' => '<i class="fa fa-list-ol"></i> '.trans('cortex/foundation::common.limit').' <span class="caret"/>'];
        $importButton = ['extend' => 'import', 'action' => "function () {
    let selectedIds = [];
    let selected = this.rows( { selected: true } );
    if (selected.count()) {
        selected.data().each(function (row) {
            selectedIds.push(row.id);
        });

        $.ajax({
            method: 'POST',
            data: {
                selected_ids: selectedIds,
                _token: window.Laravel.csrfToken,
            },
            url: window.location.pathname.replace('/import', '/hoard'),
            success: function(response) {
                let notification = function() { $.notify({message: response}, {type: 'info', mouse_over: 'pause', z_index: 9999, animate:{enter: \"animated fadeIn\", exit: \"animated fadeOut\"}}); }; if (typeof notification === 'function') { notification(); notification = null; };
                window.location.reload();
            },
        });
    }}"];

        return array_merge([
            'dom' => $this->options['dom'],
            'keys' => $this->options['keys'],
            'mark' => $this->options['mark'],
            'order' => $this->options['order'],
            'select' => $this->options['select'],
            'retrieve' => $this->options['retrieve'],
            'autoWidth' => $this->options['autoWidth'],
            'fixedHeader' => $this->options['fixedHeader'],
            'pageLength' => $this->options['pageLength'],
            'lengthMenu' => $this->options['lengthMenu'],
            'buttons' => ['print', 'reset', 'reload', $importButton, $columnsButton, $lengthButton],
        ], $this->builderParameters);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            'resource' => ['title' => trans('cortex/foundation::common.resource')],
            'status' => ['title' => trans('cortex/foundation::common.status')],
            'data' => ['title' => trans('cortex/foundation::common.data'), 'orderable' => false],
            'created_at' => ['title' => trans('cortex/foundation::common.created_at'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')"],
            'updated_at' => ['title' => trans('cortex/foundation::common.updated_at'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')"],
        ];
    }
}
