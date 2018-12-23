<?php

declare(strict_types=1);

namespace Cortex\Foundation\DataTables;

use Cortex\Foundation\Models\Log;
use Cortex\Foundation\Transformers\LogTransformer;

/**
 * @property \Spatie\Activitylog\Traits\CausesActivity $resource
 * @property string                                    $tabs
 * @property string                                    $id
 */
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
        $query = $this->resource->activities();

        return $this->applyScopes($query);
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
        return [
            'dom' => $this->dom,
            'keys' => $this->keys,
            'order' => $this->order,
            'retrieve' => $this->retrieve,
            'autoWidth' => $this->autoWidth,
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
                ['extend' => 'pageLength', 'text' => '<i class="fa fa-list-ol"></i> '.trans('cortex/foundation::common.limit').' <span class="caret"/>'],
            ],
        ];
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            'details' => ['title' => '', 'data' => null, 'defaultContent' => '', 'class' => 'dt-details-control', 'searchable' => false, 'orderable' => false],
            'causer' => ['title' => trans('cortex/foundation::common.causer'), 'name' => 'causer.username', 'searchable' => false, 'orderable' => false, 'render' => 'full.causer_route ? "<a href=\""+full.causer_route+"\">"+data+"</a>" : data', 'responsivePriority' => 0],
            'description' => ['title' => trans('cortex/foundation::common.description'), 'orderable' => false],
            'created_at' => ['title' => trans('cortex/foundation::common.date'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')"],
        ];
    }
}
