<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Cortex\Foundation\Http\FormRequest;
use Cortex\Foundation\Models\Accessarea;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Foundation\Http\Controllers\AuthorizedController;
use Cortex\Foundation\DataTables\Adminarea\AccessareasDataTable;
use Cortex\Foundation\Http\Requests\Adminarea\AccessareaFormRequest;

class AccessareasController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'cortex.foundation.models.accessarea';

    /**
     * List all accessareas.
     *
     * @param \Cortex\Foundation\DataTables\Adminarea\AccessareasDataTable $accessareasDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(AccessareasDataTable $accessareasDataTable)
    {
        return $accessareasDataTable->with([
            'id' => 'adminarea-cortex-foundation-accessareas-index',
            'routePrefix' => 'adminarea.cortex.foundation.accessareas',
            'pusher' => ['entity' => 'accessarea', 'channel' => 'cortex.foundation.accessareas.index'],
        ])->render('cortex/foundation::adminarea.pages.datatable-index');
    }

    /**
     * List accessarea logs.
     *
     * @param \Cortex\Foundation\Models\Accessarea        $accessarea
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function logs(Accessarea $accessarea, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'resource' => $accessarea,
            'tabs' => 'adminarea.cortex.foundation.accessareas.tabs',
            'id' => "adminarea-cortex-foundation-accessareas-{$accessarea->getRouteKey()}-logs",
        ])->render('cortex/foundation::adminarea.pages.datatable-tab');
    }

    /**
     * Create new accessarea.
     *
     * @param \Illuminate\Http\Request             $request
     * @param \Cortex\Foundation\Models\Accessarea $accessarea
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request, Accessarea $accessarea)
    {
        return $this->form($request, $accessarea);
    }

    /**
     * Edit given accessarea.
     *
     * @param \Illuminate\Http\Request             $request
     * @param \Cortex\Foundation\Models\Accessarea $accessarea
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, Accessarea $accessarea)
    {
        return $this->form($request, $accessarea);
    }

    /**
     * Show accessarea create/edit form.
     *
     * @param \Illuminate\Http\Request             $request
     * @param \Cortex\Foundation\Models\Accessarea $accessarea
     *
     * @return \Illuminate\View\View
     */
    protected function form(Request $request, Accessarea $accessarea)
    {
        if (! $accessarea->exists && $request->has('replicate') && $replicated = $accessarea->resolveRouteBinding($request->input('replicate'))) {
            $accessarea = $replicated->replicate();
        }

        return view('cortex/foundation::adminarea.pages.accessarea', compact('accessarea'));
    }

    /**
     * Store new accessarea.
     *
     * @param \Cortex\Foundation\Http\Requests\Adminarea\AccessareaFormRequest $request
     * @param \Cortex\Foundation\Models\Accessarea                             $accessarea
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(AccessareaFormRequest $request, Accessarea $accessarea)
    {
        return $this->process($request, $accessarea);
    }

    /**
     * Update given accessarea.
     *
     * @param \Cortex\Foundation\Http\Requests\Adminarea\AccessareaFormRequest $request
     * @param \Cortex\Foundation\Models\Accessarea                             $accessarea
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(AccessareaFormRequest $request, Accessarea $accessarea)
    {
        return $this->process($request, $accessarea);
    }

    /**
     * Process stored/updated accessarea.
     *
     * @param \Cortex\Foundation\Http\FormRequest  $request
     * @param \Cortex\Foundation\Models\Accessarea $accessarea
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function process(FormRequest $request, Accessarea $accessarea)
    {
        // Prepare required input fields
        $data = $request->validated();

        // Save accessarea
        $accessarea->fill($data)->save();

        return intend([
            'url' => route('adminarea.cortex.foundation.accessareas.index'),
            'with' => ['success' => trans('cortex/foundation::messages.resource_saved', ['resource' => trans('cortex/foundation::common.accessarea'), 'identifier' => $accessarea->name])],
        ]);
    }

    /**
     * Destroy given accessarea.
     *
     * @param \Cortex\Foundation\Models\Accessarea $accessarea
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Accessarea $accessarea)
    {
        $accessarea->delete();

        return intend([
            'url' => route('adminarea.cortex.foundation.accessareas.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => trans('cortex/foundation::common.accessarea'), 'identifier' => $accessarea->name])],
        ]);
    }
}
