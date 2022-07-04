<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Illuminate\Routing;

use Exception;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Redirector as BaseRedirector;

class Redirector extends BaseRedirector
{
    /**
     * Create a new redirect response to a named route.
     *
     * @param string $route
     * @param mixed  $parameters
     * @param int    $status
     * @param array  $headers
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function route($route, $parameters = [], $status = 302, $headers = [])
    {
        return ($previousUrl = $this->generator->getRequest()->get('previous_url'))
            ? $this->to($previousUrl) : parent::route($route, $parameters, $status, $headers);
    }

    /**
     * Create a new redirect response to the previously intended location.
     *
     * @related Save state, and redirect, or resubmit form after authentication.
     *
     * @param string    $default
     * @param int       $status
     * @param array     $headers
     * @param bool|null $secure
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function intended($default = '/', $status = 302, $headers = [], $secure = null)
    {
        $request = $this->generator->getRequest();
        $params = $this->session->get('url.params', []);
        $method = $this->session->get('url.method', 'GET');
        $intended = $this->session->get('url.intended', $default);

        // Throw an exception in case of potentially infinite redirects!
        if ($intended === url()->current() && $request->isMethod('GET')) {
            throw new Exception(trans('cortex/foundation::messages.infinite_redirects'));
        }

        // Handle POST & non-GET requests
        if ($method !== 'GET') {
            $params['_token'] = csrf_token();
            $request = $request::create($intended, $method, $params);

            return app()->handle($request);
        }

        return $this->to($intended, $status, $headers, $secure)->withInput($params);
    }

    /**
     * Set the intended method.
     *
     * @related Save state, and redirect, or resubmit form after authentication.
     *          - Scenario #1 - Authentication succeeded: session replaced by default, so all items are automatically flushed, no need to force forget!
     *          - Scenario #2 - Authentication failed: session stays, and all items are persisted across subsequent requests, until authentication!
     *          - Scenario #3 - Authentication failed, but user visits another page that triggers `saveStateUntilAuthentication` again, in that
     *                          case session values are replaced, then all items are persisted across requests, until authentication!
     *
     * @return void
     */
    public function saveStateUntilAuthentication()
    {
        $request = $this->generator->getRequest();

        $this->session->put('url.params', $request->all());
        $this->session->put('url.method', $request->method());
        $this->session->put('url.intended', Route::is('*.login') ? url()->previous() : url()->full());
    }
}
