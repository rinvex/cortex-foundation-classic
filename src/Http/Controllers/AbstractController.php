<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Rinvex\Fort\Traits\GetsMiddleware;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class AbstractController extends Controller
{
    use GetsMiddleware;
    use DispatchesJobs;
    use ValidatesRequests;
    use AuthorizesRequests;

    /**
     * Whitelisted methods.
     * Array of whitelisted methods which do not need to go through middleware.
     *
     * @var array
     */
    protected $middlewareWhitelist = [];

    /**
     * The broker name.
     *
     * @var string
     */
    protected $broker;

    /**
     * Create a new authenticated controller instance.
     */
    public function __construct()
    {
        // Set accessarea to the global request parameter bag
        $accessArea = str_before(Route::currentRouteName(), '.');
        request()->request->add(['accessarea' => $accessArea]);
    }

    /**
     * Get the broker to be used.
     *
     * @return string
     */
    protected function getBroker()
    {
        return $this->broker;
    }
}
