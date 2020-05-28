<?php

declare(strict_types=1);

namespace Cortex\Foundation\DataTables;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\Services\DataTable;

abstract class AbstractDataTable extends DataTable
{
    /**
     * The model class.
     *
     * @var string
     */
    protected $model;

    /**
     * The transformer class.
     *
     * @var string
     */
    protected $transformer;

    /**
     * Set default options.
     *
     * @var mixed
     */
    protected $options = [
        'dom' => "<'row'<'col-sm-8'B><'col-sm-4'f>> <'row'r><'row'<'col-sm-12't>> <'row'<'col-sm-5'i><'col-sm-7'p>>",
        'select' => '{"style":"multi"}',
        'order' => [[1, 'asc']],
        'mark' => true,
        'keys' => false,
        'retrieve' => true,
        'autoWidth' => false,
        'fixedHeader' => true,
        'checkbox' => true,
        'pageLength' => 10,
        'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
    ];

    /**
     * Set action buttons.
     *
     * @var mixed
     */
    protected $buttons = [
        'create' => true,
        'import' => true,

        'reset' => true,
        'reload' => true,
        'showSelected' => true,

        'print' => true,
        'export' => true,

        'bulkDelete' => true,
        'bulkActivate' => false,
        'bulkDeactivate' => false,

        'colvis' => true,
        'pageLength' => true,
    ];

    /**
     * The datatable builder parameters.
     *
     * @var array
     */
    protected $builderParameters = [];

    /**
     * Get columns.
     *
     * @return array
     */
    abstract protected function getColumns(): array;

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $model = app($this->model);
        $query = $model->query();

        if (! empty($selectedIds = $this->request->get('selected_ids'))) {
            $obscure = property_exists($model, 'obscure') && is_array($model->obscure) ? $model->obscure : config('cortex.foundation.obscure');

            if (in_array(request()->route('accessarea'), $obscure['areas'])) {
                $selectedIds = collect($selectedIds)->map(function ($value) {
                    return optional(Hashids::decode($value))[0];
                });

                $query->whereIn($model->getKeyName(), $selectedIds);
            } else {
                $query->whereIn($model->getRouteKeyName(), $selectedIds);
            }
        }

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
            ->orderColumn('name', 'name->"$.'.app()->getLocale().'" $1')
            ->make(true);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $data = <<<CDATA
function(data){
    var formData = $("{$this->getAjaxForm()}").find("input, select").serializeArray();
    $.each(formData, function(i, obj){
        data[obj.name] = obj.value;
    });
}
CDATA;

        return $this->builder()
                    ->columns($this->getColumns())
                    ->parameters($this->getBuilderParameters())
                    ->postAjax(['url' => $this->getAjaxUrl(), 'data' => $data]);
    }

    /**
     * Process DataTables needed render output.
     *
     * @param string $view
     * @param array  $data
     * @param array  $mergeData
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function render($view, $data = [], $mergeData = [])
    {
        if ($this->request()->ajax() && $this->request()->wantsJson()) {
            return app()->call([$this, 'ajax']);
        }

        if (($action = $this->request()->get('action')) && in_array($action, $this->actions)) {
            if ($action === 'print') {
                return app()->call([$this, 'printPreview']);
            }

            return app()->call([$this, $action]);
        }

        return view($view, array_merge($this->attributes, $data), $mergeData)->with($this->dataTableVariable, $this->getHtmlBuilder());
    }

    /**
     * Get default builder parameters.
     *
     * @return array
     */
    protected function getBuilderParameters(): array
    {
        $text = [
            'create' => '<i class="fa fa-plus"></i> '.trans('cortex/foundation::common.create'),
            'import' => '<i class="fa fa-upload"></i> '.trans('cortex/foundation::common.import'),

            'bulkDelete' => '<i class="fa fa-trash"></i> '.trans('cortex/foundation::common.bulkDelete'),
            'bulkEnable' => '<i class="fa fa-power-off"></i> '.trans('cortex/foundation::common.bulkEnable'),
            'bulkDisable' => '<i class="fa fa-power-off"></i> '.trans('cortex/foundation::common.bulkDisable'),

            'reset' => '<i class="fa fa-undo"></i> '.trans('cortex/foundation::common.reset'),
            'reload' => '<i class="fa fa-refresh"></i> '.trans('cortex/foundation::common.reload'),

            'print' => '<i class="fa fa-print"></i> '.trans('cortex/foundation::common.print'),
            'export' => '<i class="fa fa-download"></i> '.trans('cortex/foundation::common.export'),
            'showSelected' => '<i class="fa fa-check"></i> '.trans('cortex/foundation::common.showSelected'),

            'colvis' => '<i class="fa fa-columns"></i> '.trans('cortex/foundation::common.colvis').' <span class="caret"></span>',
            'pageLength' => '<i class="fa fa-list-ol"></i> '.trans('cortex/foundation::common.pageLength').' <span class="caret"></span>',
        ];

        $buttons = collect($this->buttons)->filter(function ($value) {
            return $value;
        })->keys()->map(function ($value) use ($text) {
            return ['extend' => $value, 'text' => $text[$value]];
        });

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
            'buttons' => $buttons,
            'initComplete' => $this->getAjaxForm() ? "function () {
                $('".$this->getAjaxForm()."').on('change',  (e)=> {
                    e.preventDefault();
                    this.api().draw();
                });
            }" : '',
        ], $this->builderParameters);
    }

    /**
     * Get Ajax URL.
     *
     * @return string
     */
    protected function getAjaxUrl(): string
    {
        return '';
    }

    /**
     * Get Ajax form.
     *
     * @return string
     */
    protected function getAjaxForm(): string
    {
        return '';
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        $model = $this->model ?? trim(str_replace('DataTable', '', mb_strrchr(static::class, '\\')), " \t\n\r\0\x0B\\");

        $resource = Str::plural(mb_strtolower(Arr::last(explode(class_exists($model) ? '\\' : '.', $model))));

        return $resource.'-export-'.date('Y-m-d').'-'.time();
    }
}
