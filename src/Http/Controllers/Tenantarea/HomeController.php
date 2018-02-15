<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Controllers\Tenantarea;

use Cortex\Foundation\Http\Controllers\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Show tenantarea index.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('cortex/foundation::tenantarea.pages.index');
    }
}
