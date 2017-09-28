<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Controllers\Tenantarea;

use Cortex\Foundation\Http\Controllers\AuthorizedController;

class HomeController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'tenantarea';

    /**
     * {@inheritdoc}
     */
    protected $resourceAbilityMap = ['index' => 'access'];

    /**
     * Show the tenantarea home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cortex/foundation::tenantarea.pages.home');
    }
}
