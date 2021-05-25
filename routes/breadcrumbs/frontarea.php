<?php

declare(strict_types=1);

use Diglactic\Breadcrumbs\Generator;
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('frontarea.home', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-home"></i> '.config('app.name'), route('frontarea.home'));
});
