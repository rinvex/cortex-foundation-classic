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
    protected $createButton = false;

    /**
     * {@inheritdoc}
     */
    protected $builderParameters = [
        'initComplete' => 'function (settings) {
            implicitForms.initialize();
        }',
    ];

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
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
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
