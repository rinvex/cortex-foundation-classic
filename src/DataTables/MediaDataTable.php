<?php

declare(strict_types=1);

namespace Cortex\Foundation\DataTables;

use Spatie\MediaLibrary\Models\Media;
use Cortex\Foundation\Transformers\MediaTransformer;

class MediaDataTable extends AbstractDataTable
{
    /**
     * {@inheritdoc}
     */
    protected $model = Media::class;

    /**
     * {@inheritdoc}
     */
    protected $transformer = MediaTransformer::class;

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = $this->resource->media()->orderBy('order_column', 'ASC');

        return $this->applyScopes($query);
    }

    /**
     * Get default builder parameters.
     *
     * @return array
     */
    protected function getBuilderParameters()
    {
        return [
            'keys' => true,
            'retrieve' => true,
            'autoWidth' => false,
            'dom' => "<'row'<'col-sm-6'B><'col-sm-6'f>> <'row'r><'row'<'col-sm-12't>> <'row'<'col-sm-5'i><'col-sm-7'p>>",
            'buttons' => [
                'print', 'reset', 'reload', 'export',
                ['extend' => 'colvis', 'text' => '<i class="fa fa-columns"></i> '.trans('cortex/foundation::common.columns').' <span class="caret"/>'],
                ['extend' => 'pageLength', 'text' => '<i class="fa fa-list-ol"></i> '.trans('cortex/foundation::common.limit').' <span class="caret"/>'],
            ],
            'initComplete' => 'function (settings) {
                implicitForms.initialize();
            }',
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
            'name' => ['title' => trans('cortex/foundation::common.name'), 'responsivePriority' => 0],
            'file_name' => ['title' => trans('cortex/foundation::common.file_name')],
            'mime_type' => ['title' => trans('cortex/foundation::common.mime_type')],
            'size' => ['title' => trans('cortex/foundation::common.size')],
            'created_at' => ['title' => trans('cortex/foundation::common.created_at'), 'render' => "moment(data).format('MMM Do, YYYY')"],
            'updated_at' => ['title' => trans('cortex/foundation::common.updated_at'), 'render' => "moment(data).format('MMM Do, YYYY')"],
            'delete' => ['title' => trans('cortex/foundation::common.delete'), 'orderable' => false, 'searchable' => false, 'render' => '"<a href=\"#\" data-toggle=\"modal\" data-target=\"#delete-confirmation\" data-modal-action=\""+data+"\" data-modal-title=\"'.trans('cortex/foundation::messages.delete_confirmation_title').'\" data-modal-body=\"" + Lang.trans(\'cortex/foundation::messages.delete_confirmation_body\', {type: \'media\', name: full.name}) + "\" title=\"'.trans('cortex/foundation::common.delete').'\"><i class=\"fa fa-trash text-danger\"></i></a>"'],
        ];
    }
}
