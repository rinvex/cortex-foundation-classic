<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Illuminate\Routing;

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Redirector as BaseRedirector;

class Redirector extends BaseRedirector
{
    /**
     * Create a new redirect response to a named route.
     *
     * @param  string  $route
     * @param  mixed  $parameters
     * @param  int  $status
     * @param  array  $headers
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
     * @param  string  $default
     * @param  int  $status
     * @param  array  $headers
     * @param  bool|null  $secure
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Client\Response
     */
    public function intended($default = '/', $status = 302, $headers = [], $secure = null)
    {
        $request = $this->generator->getRequest();
        $intended = $this->session->pull('url.intended', $default);
        $method = $this->session->pull('url.method', 'GET');
        $params = $this->session->pull('url.params', []);

        if ($request->isMethod($method)) {
            $params['_token'] = csrf_token();

            $this->session->put('url.intended', $intended);

            $request = $request::create($intended, $method, $params);

            return app()->handle($request);
        }

        return $this->to($intended, $status, $headers, $secure)->withInput($params);
    }

    /**
     * Set the intended method.
     *
     * @return void
     */
    public function afterAuthentication()
    {
        $request = $this->generator->getRequest();
        $intended = Route::is('*.login') ? url()->previous() : url()->current();

        $this->session->put('url.intended', $intended);
        $this->session->put('url.intended', $intended);
        $this->session->put('url.params', $request->all());
        $this->session->put('url.method', $request->method());
    }
}
