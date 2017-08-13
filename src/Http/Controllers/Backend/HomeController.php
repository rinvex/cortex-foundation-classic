<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Controllers\Backend;

use Cortex\Foundation\Http\Controllers\AuthorizedController;

class HomeController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'dashboard';

    /**
     * {@inheritdoc}
     */
    protected $resourceAbilityMap = ['home' => 'access'];

    /**
     * Show the dashboard home.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        return view('cortex/foundation::backend.pages.home');
    }
}
