<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Controllers\Frontarea;

use Cortex\Foundation\Http\Controllers\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Show frontarea index.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('cortex/foundation::frontarea.pages.index');
    }
}
