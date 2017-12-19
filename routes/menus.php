<?php

declare(strict_types=1);

use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Factories\MenuFactory;

Menu::modify('adminarea.sidebar', function (MenuFactory $menu) {
    $menu->dropdown(function (MenuItem $dropdown) {
    }, trans('cortex/foundation::common.taxonomy'), 10, 'fa fa-arrows');
});
Menu::modify('adminarea.sidebar', function (MenuFactory $menu) {
    $menu->dropdown(function (MenuItem $dropdown) {
    }, trans('cortex/foundation::common.access'), 20, 'fa fa-user-circle-o');
});
Menu::modify('adminarea.sidebar', function (MenuFactory $menu) {
    $menu->dropdown(function (MenuItem $dropdown) {
    }, trans('cortex/foundation::common.user'), 30, 'fa fa-users');
});
Menu::modify('adminarea.sidebar', function (MenuFactory $menu) {
    $menu->dropdown(function (MenuItem $dropdown) {
    }, trans('cortex/foundation::common.cms'), 40, 'fa fa-file-text-o');
});
Menu::modify('adminarea.sidebar', function (MenuFactory $menu) {
    $menu->dropdown(function (MenuItem $dropdown) {
    }, trans('cortex/foundation::common.crm'), 50, 'fa fa-briefcase');
});
Menu::modify('adminarea.sidebar', function (MenuFactory $menu) {
    $menu->dropdown(function (MenuItem $dropdown) {
    }, trans('cortex/foundation::common.maintenance'), 999, 'fa fa-cogs');
});

// @TODO: Move this code block to cortex/tenants once we know how to order service provider execution
//        We need these menus to be registered first before any other modules start attaching items to it!
Menu::modify('managerarea.sidebar', function (MenuFactory $menu) {
    $menu->dropdown(function (MenuItem $dropdown) {
    }, trans('cortex/foundation::common.access'), 20, 'fa fa-user-circle-o');
});
Menu::modify('managerarea.sidebar', function (MenuFactory $menu) {
    $menu->dropdown(function (MenuItem $dropdown) {
    }, trans('cortex/foundation::common.user'), 30, 'fa fa-users');
});
Menu::modify('managerarea.sidebar', function (MenuFactory $menu) {
    $menu->dropdown(function (MenuItem $dropdown) {
    }, trans('cortex/foundation::common.cms'), 40, 'fa fa-file-text-o');
});
Menu::modify('managerarea.sidebar', function (MenuFactory $menu) {
    $menu->dropdown(function (MenuItem $dropdown) {
    }, trans('cortex/foundation::common.crm'), 50, 'fa fa-briefcase');
});

if (config('cortex.foundation.route.locale_prefix')) {
    $languageMenu = function (MenuFactory $menu) {
        $menu->dropdown(function (MenuItem $dropdown) {
            foreach (app('laravellocalization')->getSupportedLocales() as $key => $locale) {
                $dropdown->url(app('laravellocalization')->localizeURL(request()->fullUrl(), $key), $locale['name']);
            }
        }, app('laravellocalization')->getCurrentLocaleNative(), 10, 'fa fa-globe');
    };

    Menu::modify('frontarea.header', $languageMenu);
    Menu::modify('adminarea.header', $languageMenu);
}
