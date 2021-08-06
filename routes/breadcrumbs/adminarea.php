<?php

declare(strict_types=1);

use Diglactic\Breadcrumbs\Generator;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Cortex\Foundation\Models\Accessarea;

Breadcrumbs::for('adminarea.cortex.foundation.accessareas.index', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/foundation::common.accessareas'), route('adminarea.cortex.foundation.accessareas.index'));
});

Breadcrumbs::for('adminarea.cortex.foundation.accessareas.import', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.foundation.accessareas.index');
    $breadcrumbs->push(trans('cortex/foundation::common.import'), route('adminarea.cortex.foundation.accessareas.import'));
});

Breadcrumbs::for('adminarea.cortex.foundation.accessareas.import.logs', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.foundation.accessareas.index');
    $breadcrumbs->push(trans('cortex/foundation::common.import'), route('adminarea.cortex.foundation.accessareas.import'));
    $breadcrumbs->push(trans('cortex/foundation::common.logs'), route('adminarea.cortex.foundation.accessareas.import.logs'));
});

Breadcrumbs::for('adminarea.cortex.foundation.accessareas.create', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.foundation.accessareas.index');
    $breadcrumbs->push(trans('cortex/foundation::common.create_accessarea'), route('adminarea.cortex.foundation.accessareas.create'));
});

Breadcrumbs::for('adminarea.cortex.foundation.accessareas.edit', function (Generator $breadcrumbs, Accessarea $accessarea) {
    $breadcrumbs->parent('adminarea.cortex.foundation.accessareas.index');
    $breadcrumbs->push(strip_tags($accessarea->name), route('adminarea.cortex.foundation.accessareas.edit', ['accessarea' => $accessarea]));
});

Breadcrumbs::for('adminarea.cortex.foundation.accessareas.logs', function (Generator $breadcrumbs, Accessarea $accessarea) {
    $breadcrumbs->parent('adminarea.cortex.foundation.accessareas.index');
    $breadcrumbs->push(strip_tags($accessarea->name), route('adminarea.cortex.foundation.accessareas.edit', ['accessarea' => $accessarea]));
    $breadcrumbs->push(trans('cortex/foundation::common.logs'), route('adminarea.cortex.foundation.accessareas.logs', ['accessarea' => $accessarea]));
});

Breadcrumbs::for('adminarea.cortex.foundation.accessareas.media.index', function (Generator $breadcrumbs, Accessarea $accessarea) {
    $breadcrumbs->parent('adminarea.cortex.foundation.accessareas.index');
    $breadcrumbs->push(strip_tags($accessarea->name), route('adminarea.cortex.foundation.accessareas.edit', ['accessarea' => $accessarea]));
    $breadcrumbs->push(trans('cortex/foundation::common.media'), route('adminarea.cortex.foundation.accessareas.media.index', ['accessarea' => $accessarea]));
});
