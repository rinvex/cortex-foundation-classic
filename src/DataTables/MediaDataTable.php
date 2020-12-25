<?php

declare(strict_types=1);

namespace Cortex\Foundation\DataTables;

use Cortex\Foundation\Transformers\MediaTransformer;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property \Spatie\MediaLibrary\HasMedia $resource
 * @property string                        $tabs
 * @property string                        $id
 * @property string                        $url
 */
class MediaDataTable extends AbstractDataTable
{
    /**
     * {@inheritdoc}
     */
    protected $model = Media::class;

    /**
     * Set action buttons.
     *
     * @var mixed
     */
    protected $buttons = [
        'create' => false,
        'import' => false,
        'create_popup' => false,

        'reset' => true,
        'reload' => true,
        'showSelected' => true,

        'print' => true,
        'export' => true,

        'bulkDelete' => true,
        'bulkActivate' => false,
        'bulkDeactivate' => false,
        'bulkRevoke' => false,

        'colvis' => true,
        'pageLength' => true,
    ];

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
     * @TODO: Apply row selection and bulk actions, check parent::query() for reference.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = $this->resource->media();

        return $this->scope()->applyScopes($query);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            'id' => ['checkboxes' => '{"selectRow": true}', 'exportable' => false, 'printable' => false],
            'name' => ['title' => trans('cortex/foundation::common.name'), 'responsivePriority' => 0],
            'file_name' => ['title' => trans('cortex/foundation::common.file_name')],
            'mime_type' => ['title' => trans('cortex/foundation::common.mime_type')],
            'size' => ['title' => trans('cortex/foundation::common.size')],
            'created_at' => ['title' => trans('cortex/foundation::common.created_at'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')"],
            'updated_at' => ['title' => trans('cortex/foundation::common.updated_at'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')"],
            'delete' => ['title' => trans('cortex/foundation::common.delete'), 'orderable' => false, 'searchable' => false, 'render' => '"<a href=\"#\" data-toggle=\"modal\" data-target=\"#delete-confirmation\"
                data-modal-action=\""+data+"\"
                data-modal-title=\"" + Lang.trans(\'cortex/foundation::messages.delete_confirmation_title\') + "\"
                data-modal-button=\"<a href=\'#\' class=\'btn btn-danger\' data-form=\'delete\' data-token=\''.csrf_token().'\'><i class=\'fa fa-trash-o\'></i> \"" + Lang.trans(\'cortex/foundation::common.delete\') + "\"</a>\"
                data-modal-body=\"" + Lang.trans(\'cortex/foundation::messages.delete_confirmation_body\', {type: \'media\', name: full.name}) + "\"
                title=\"" + Lang.trans(\'cortex/foundation::common.delete\') + "\"><i class=\"fa fa-trash text-danger\"></i></a>"'],
        ];
    }
}
