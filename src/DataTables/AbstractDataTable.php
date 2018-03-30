<?php

declare(strict_types=1);

namespace Cortex\Foundation\DataTables;

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
     * The datatable dom parameter.
     *
     * @var string
     */
    protected $dom = "<'row'<'col-sm-8'B><'col-sm-4'f>> <'row'r><'row'<'col-sm-12't>> <'row'<'col-sm-5'i><'col-sm-7'p>>";

    /**
     * The datatable keys parameter.
     *
     * @var bool
     */
    protected $keys = true;

    /**
     * The datatable mark parameter.
     *
     * @var bool
     */
    protected $mark = true;

    /**
     * The datatable order parameter.
     *
     * @var array
     */
    protected $order = [[0, 'asc']];

    /**
     * The datatable retrieve parameter.
     *
     * @var array
     */
    protected $retrieve = true;

    /**
     * The datatable autoWidth parameter.
     *
     * @var array
     */
    protected $autoWidth = false;

    /**
     * The datatable searchPane parameter.
     *
     * @var array
     */
    protected $searchPane = false;

    /**
     * The datatable fixedHeader parameter.
     *
     * @var array
     */
    protected $fixedHeader = true;

    /**
     * The datatable create parameter.
     *
     * @var bool
     */
    protected $createButton = true;

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
    abstract protected function getColumns();

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = app($this->model)->query();

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
            ->setTransformer($this->transformer)
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
        return $this->builder()
                    ->minifiedAjax()
                    ->columns($this->getColumns())
                    ->parameters($this->getBuilderParameters());
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
        return array_merge([
            'dom' => $this->dom,
            'keys' => $this->keys,
            'mark' => $this->mark,
            'order' => $this->order,
            'retrieve' => $this->retrieve,
            'autoWidth' => $this->autoWidth,
            'searchPane' => $this->searchPane,
            'fixedHeader' => $this->fixedHeader,
            'buttons' => array_merge($this->createButton ? [['extend' => 'create', 'text' => '<i class="fa fa-plus"></i> '.trans('cortex/foundation::common.new')]] : [], [
                'print', 'reset', 'reload', 'import', 'export',
                ['extend' => 'colvis', 'text' => '<i class="fa fa-columns"></i> '.trans('cortex/foundation::common.columns').' <span class="caret"/>'],
                ['extend' => 'pageLength', 'text' => '<i class="fa fa-list-ol"></i> '.trans('cortex/foundation::common.limit').' <span class="caret"/>'],
            ]),
        ], $this->builderParameters);
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        $model = $this->model ?? trim(str_replace('DataTable', '', mb_strrchr(static::class, '\\')), " \t\n\r\0\x0B\\");

        $resource = str_plural(mb_strtolower(array_last(explode(class_exists($model) ? '\\' : '.', $model))));

        return $resource.'-export-'.date('Y-m-d').'-'.time();
    }
}
