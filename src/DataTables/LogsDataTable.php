<?php

declare(strict_types=1);

namespace Cortex\Foundation\DataTables;

use Cortex\Foundation\Models\Log;
use Cortex\Foundation\Transformers\Backend\LogTransformer;

class LogsDataTable extends AbstractDataTable
{
    /**
     * {@inheritdoc}
     */
    protected $model = Log::class;

    /**
     * {@inheritdoc}
     */
    protected $transformer = LogTransformer::class;

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = $this->resource->activity();

        return $this->applyScopes($query);
    }

    /**
     * Get parameters.
     *
     * @return array
     */
    protected function getParameters()
    {
        return [
            'keys' => true,
            'autoWidth' => false,
            'order' => [1 => 'asc'],
            'dom' => "<'row'<'col-sm-6'B><'col-sm-6'f>> <'row'r><'row'<'col-sm-12't>> <'row'<'col-sm-5'i><'col-sm-7'p>>",
            'drawCallback' => "function (settings) {
                var api = this.api();

                $('#{$this->id} tbody td.dt-details-control').on('click', function () {
                    var tr = $(this).closest('tr');
                    var row = api.row(tr);

                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        row.child(dtFormatLogDetails(row.data().properties)).show();
                        tr.addClass('shown');
                    }
                });
            }",
            'buttons' => [
                'print', 'reset', 'reload', 'export',
                ['extend' => 'colvis', 'text' => '<i class="fa fa-columns"></i> '.trans('cortex/foundation::common.columns').' <span class="caret"/>'],
            ],
        ];
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'details' => ['title' => '', 'data' => null, 'defaultContent' => '', 'class' => 'dt-details-control', 'searchable' => false, 'orderable' => false],
            'causer' => ['title' => trans('cortex/foundation::common.causer'), 'name' => 'causer.username', 'searchable' => false, 'orderable' => false, 'render' => 'full.causer_route ? "<a href=\""+full.causer_route+"\">"+data+"</a>" : data', 'responsivePriority' => 0],
            'description' => ['title' => trans('cortex/foundation::common.description'), 'orderable' => false],
            'created_at' => ['title' => trans('cortex/foundation::common.date'), 'render' => "moment(data).format('MMM Do, YYYY - hh:mm:ss A')"],
        ];
    }
}
