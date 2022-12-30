<?php

declare(strict_types=1);

namespace Cortex\Foundation\DataTables\Adminarea;

use Illuminate\Http\JsonResponse;
use Cortex\Foundation\Models\Accessarea;
use Cortex\Foundation\DataTables\AbstractDataTable;
use Cortex\Foundation\Transformers\AccessareaTransformer;

class AccessareasDataTable extends AbstractDataTable
{
    /**
     * {@inheritdoc}
     */
    protected $model = Accessarea::class;

    /**
     * {@inheritdoc}
     */
    protected $transformer = AccessareaTransformer::class;

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax(): JsonResponse
    {
        return datatables($this->query())
            ->setTransformer(app($this->transformer))
            ->orderColumn('name', 'name->"$.'.app()->getLocale().'" $1')
            ->whitelist(array_keys($this->getColumns()))
            ->make(true);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        $link = config('cortex.foundation.route.locale_prefix')
            ? '"<a href=\""+routes.route(\'adminarea.cortex.foundation.accessareas.edit\', {accessarea: full.id, locale: \''.$this->request()->segment(1).'\'})+"\">"+data+"</a>"'
            : '"<a href=\""+routes.route(\'adminarea.cortex.foundation.accessareas.edit\', {accessarea: full.id})+"\">"+data+"</a>"';

        return [
            'id' => ['checkboxes' => json_decode('{"selectRow": true}'), 'exportable' => false, 'printable' => false],
            'name' => ['title' => trans('cortex/foundation::common.name'), 'render' => $link.'+(full.is_active ? " <i class=\"text-success fa fa-check\"></i>" : " <i class=\"text-danger fa fa-close\"></i>")', 'responsivePriority' => 0],
            'is_active' => ['title' => trans('cortex/foundation::common.is_active')],
            'is_obscured' => ['title' => trans('cortex/foundation::common.is_obscured')],
            'created_at' => ['title' => trans('cortex/foundation::common.created_at'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')"],
            'updated_at' => ['title' => trans('cortex/foundation::common.updated_at'), 'render' => "moment(data).format('YYYY-MM-DD, hh:mm:ss A')"],
        ];
    }
}
