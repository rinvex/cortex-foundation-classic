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
     * Available button actions. When calling an action, the value will be used
     * as the function name (so it should be available)
     * If you want to add or disable an action, overload and modify this property.
     *
     * @var array
     */
    protected $actions = ['print', 'csv', 'excel', 'pdf', 'delete', 'activate', 'deactivate'];

    /**
     * Set default options.
     *
     * @var array
     */
    protected $options;

    /**
     * Set action buttons.
     *
     * @var array
     */
    protected $buttons;

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
     * Create new instance of datatables.
     */
    public function __construct()
    {
        $this->options = $this->options ?? config("cortex.foundation.datatables.options");
        $this->buttons = $this->buttons ?? config("cortex.foundation.datatables.buttons");
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $model = app($this->model);
        $query = $model->query();
        $selectedIds = collect($this->request()->get('selected_ids'))->filter();

        if ($selectedIds->isNotEmpty()) {
            $obscure = property_exists($model, 'obscure') && is_array($model->obscure) ? $model->obscure : config('cortex.foundation.obscure');

            if (in_array(app('request.accessarea'), $obscure['areas'])) {
                $selectedIds = $selectedIds->map(function ($value) {
                    return optional(Hashids::decode($value))[0];
                });

                $query->whereIn($model->getKeyName(), $selectedIds);
            } else {
                $query->whereIn($model->getRouteKeyName(), $selectedIds);
            }
        }

        return $this->scope()->applyScopes($query);
    }

    /**
     * Add scopes to the datatable.
     *
     * @return $this
     */
    public function scope()
    {
        return $this;
    }

    /**
     * Perform bulk action.
     *
     * @param string $action
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function bulkAction(string $action)
    {
        if ($results = $this->query()->get()) {
            $results->each(function ($item) use ($action) {
                // Check if current user can execute this action on that model
                if ($this->request()->user(app('request.guard'))->can($action, $item)) {
                    $item->{$action};
                }
            });

            return intend([
                'back' => true,
                'with' => ['success' => trans("cortex/foundation::messages.records_{$action}d")],
            ]);
        }

        return intend([
            'back' => true,
            'with' => ['warning' => trans("cortex/foundation::messages.no_records_selected")],
        ]);
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
        if (($action = $this->request()->get('action')) && in_array($action, $this->actions)) {
            switch ($action) {
                case 'print':
                    return app()->call([$this, 'printPreview']);
                case 'revoke':
                case 'delete':
                case 'activate':
                case 'deactivate':
                    return app()->call([$this, 'bulkAction'], ['action' => $action]);
                default:
                    return app()->call([$this, $action]);
            }
        }

        if ($this->request()->ajax() && $this->request()->wantsJson()) {
            return app()->call([$this, 'ajax']);
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
        $buttons = $this->getButtons();

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
     * Get buttons for datatable.
     *
     * @return array
     */
    protected function getButtons(): array
    {
        $this->buttons['bulk'] = $this->buttons['bulkDelete']
                                || $this->buttons['bulkActivate']
                                || $this->buttons['bulkDeactivate']
                                || $this->buttons['bulkRevoke'];

        $buttons = collect($this->buttons)->filter(fn ($value) => $value);
        $bulkButtons = $buttons->only(['bulkDelete', 'bulkActivate', 'bulkDeactivate', 'bulkRevoke']);

        return collect([
            'create' => ['extend' => 'create', 'text' => '<i class="fa fa-plus"></i> '.trans('cortex/foundation::common.create')],
            'import' => ['extend' => 'import', 'text' => '<i class="fa fa-upload"></i> '.trans('cortex/foundation::common.import')],
            'create_popup' => ['extend' => 'create_popup', 'text' => '<i class="fa fa-plus"></i> '.trans('cortex/foundation::common.create')],

            'reset' => ['extend' => 'reset', 'text' => '<i class="fa fa-undo"></i> '.trans('cortex/foundation::common.reset')],
            'reload' => ['extend' => 'reload', 'text' => '<i class="fa fa-refresh"></i> '.trans('cortex/foundation::common.reload')],
            'showSelected' => ['extend' => 'showSelected', 'text' => '<i class="fa fa-check"></i> '.trans('cortex/foundation::common.showSelected')],

            'print' => ['extend' => 'print', 'text' => '<i class="fa fa-print"></i> '.trans('cortex/foundation::common.print')],
            'export' => ['extend' => 'export', 'text' => '<i class="fa fa-download"></i> '.trans('cortex/foundation::common.export').'&nbsp;<span class="caret"/>', 'autoClose' => true, 'fade' => 0],

            'bulk' => ['extend' => 'bulk', 'text' => '<i class="fa fa-list"></i> '.trans('cortex/foundation::common.bulk').'&nbsp;<span class="caret"/>', 'buttons' => $bulkButtons->keys(), 'autoClose' => true, 'fade' => 0],
            'colvis' => ['extend' => 'colvis', 'text' => '<i class="fa fa-columns"></i> '.trans('cortex/foundation::common.colvis').'&nbsp;<span class="caret"/>', 'fade' => 0],
            'pageLength' => ['extend' => 'pageLength', 'text' => '<i class="fa fa-list-ol"></i> '.trans('cortex/foundation::common.pageLength').'&nbsp;<span class="caret"/>', 'fade' => 0],
        ])->only($buttons->keys())->values()->toArray();
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
