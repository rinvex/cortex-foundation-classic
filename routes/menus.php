<?php

declare(strict_types=1);

use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Models\MenuGenerator;

if (config('cortex.foundation.route.locale_prefix')) {
    $languageMenu = function (MenuGenerator $menu) {
        $menu->dropdown(function (MenuItem $dropdown) {
            foreach (app('laravellocalization')->getSupportedLocales() as $key => $locale) {
                $dropdown->url(app('laravellocalization')->localizeURL(request()->fullUrl(), $key), $locale['name']);
            }
        }, app('laravellocalization')->getCurrentLocaleNative(), 10, 'fa fa-globe');
    };

    Menu::register('frontarea.header', $languageMenu);
    Menu::register('adminarea.header', $languageMenu);
}
