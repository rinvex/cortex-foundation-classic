<?php

declare(strict_types=1);

use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Models\MenuGenerator;
use Cortex\Foundation\Models\Accessarea;

if (config('cortex.foundation.route.locale_prefix')) {
    Menu::register('adminarea.header.language', function (MenuGenerator $menu) {
        $menu->dropdown(function (MenuItem $dropdown) {
            foreach (app('laravellocalization')->getSupportedLocales() as $key => $locale) {
                $dropdown->url(app('laravellocalization')->localizeURL(request()->fullUrl(), $key), $locale['name']);
            }
        }, app('laravellocalization')->getCurrentLocaleNative(), 10, 'fa fa-globe');
    });
}

Menu::register('adminarea.sidebar', function (MenuGenerator $menu) {
    $menu->findByTitleOrAdd(trans('cortex/foundation::common.cms'), 40, 'fa fa-file-text-o', 'header', [], [], function (MenuItem $dropdown) {
        $dropdown->route(['adminarea.cortex.foundation.accessareas.index'], trans('cortex/foundation::common.accessareas'), 20, 'fa fa-files-o')->ifCan('list', app('cortex.foundation.accessarea'))->activateOnRoute('adminarea.cortex.foundation.accessareas');
    });
});

Menu::register('adminarea.cortex.foundation.accessareas.tabs', function (MenuGenerator $menu, Accessarea $accessarea) {
    $menu->route(['adminarea.cortex.foundation.accessareas.import'], trans('cortex/foundation::common.records'))->ifCan('import', $accessarea)->if(Route::is('adminarea.cortex.foundation.accessareas.import*'));
    $menu->route(['adminarea.cortex.foundation.accessareas.import.logs'], trans('cortex/foundation::common.logs'))->ifCan('import', $accessarea)->if(Route::is('adminarea.cortex.foundation.accessareas.import*'));
    $menu->route(['adminarea.cortex.foundation.accessareas.create'], trans('cortex/foundation::common.details'))->ifCan('create', $accessarea)->if(Route::is('adminarea.cortex.foundation.accessareas.create'));
    $menu->route(['adminarea.cortex.foundation.accessareas.edit', ['accessarea' => $accessarea]], trans('cortex/foundation::common.details'))->ifCan('update', $accessarea)->if($accessarea->exists);
    $menu->route(['adminarea.cortex.foundation.accessareas.logs', ['accessarea' => $accessarea]], trans('cortex/foundation::common.logs'))->ifCan('audit', $accessarea)->if($accessarea->exists);
});
