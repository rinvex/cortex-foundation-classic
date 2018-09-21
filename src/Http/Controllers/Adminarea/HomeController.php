<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Controllers\Adminarea;

use Cortex\Foundation\Http\Controllers\AuthorizedController;

class HomeController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'access-adminarea';

    /**
     * Show the adminarea index.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('cortex/foundation::adminarea.pages.index');
    }
}
