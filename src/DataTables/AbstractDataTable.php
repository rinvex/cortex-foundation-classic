<?php

declare(strict_types=1);

namespace Cortex\Foundation\DataTables;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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
     * The datatable select parameter.
     *
     * @var bool
     */
    protected $select = true;

    /**
     * The datatable keys parameter.
     *
     * @var bool
     */
    protected $keys = false;

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
    abstract protected function getColumns(): array;

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
        return $this->builder()
                    ->columns($this->getColumns())
                    ->parameters($this->getBuilderParameters())
                    ->ajaxWithForm($this->getAjaxUrl(), $this->getAjaxForm());
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
        $createButton = ['extend' => 'create', 'text' => '<i class="fa fa-plus"></i> '.trans('cortex/foundation::common.new')];
        $columnsButton = ['extend' => 'colvis', 'text' => '<i class="fa fa-columns"></i> '.trans('cortex/foundation::common.columns').' <span class="caret"/>'];
        $lengthButton = ['extend' => 'pageLength', 'text' => '<i class="fa fa-list-ol"></i> '.trans('cortex/foundation::common.limit').' <span class="caret"/>'];

        return array_merge([
            'dom' => $this->dom,
            'keys' => $this->keys,
            'mark' => $this->mark,
            'order' => $this->order,
            'select' => $this->select,
            'retrieve' => $this->retrieve,
            'autoWidth' => $this->autoWidth,
            'fixedHeader' => $this->fixedHeader,
            'buttons' => $this->createButton
                ? [$createButton, 'print', 'reset', 'reload', 'import', 'export', $columnsButton, $lengthButton]
                : ['print', 'reset', 'reload', 'export', $columnsButton, $lengthButton],
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
