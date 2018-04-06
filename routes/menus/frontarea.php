<?php

declare(strict_types=1);

use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Models\MenuGenerator;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;

Breadcrumbs::register('frontarea.home', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('frontarea.home'));
});

if (config('cortex.foundation.route.locale_prefix')) {
    Menu::register('frontarea.header.language', function (MenuGenerator $menu) {
        $menu->dropdown(function (MenuItem $dropdown) {
            foreach (app('laravellocalization')->getSupportedLocales() as $key => $locale) {
                $dropdown->url(app('laravellocalization')->localizeURL(request()->fullUrl(), $key), $locale['name']);
            }
        }, app('laravellocalization')->getCurrentLocaleNative(), 10, 'fa fa-globe');
    });
}

Menu::register('frontarea.header.navigation', function (MenuGenerator $menu) {
    $menu->url(route('frontarea.home'), 'Home', null, null, ['class' => 'smothscroll'])->if(! Route::is('frontarea.home'));
    $menu->url('#home', 'Home', null, null, ['class' => 'smothscroll'])->if(Route::is('frontarea.home'));
    $menu->url('#desc', 'Description', null, null, ['class' => 'smothscroll'])->if(Route::is('frontarea.home'));
    $menu->url('#contact', 'Contact', null, null, ['class' => 'smothscroll'])->if(Route::is('frontarea.home'));
});
