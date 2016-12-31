<?php

/*
 * NOTICE OF LICENSE
 *
 * Part of the Cortex Foundation Module.
 *
 * This source file is subject to The MIT License (MIT)
 * that is bundled with this package in the LICENSE file.
 *
 * Package: Cortex Foundation Module
 * License: The MIT License (MIT)
 * Link:    https://rinvex.com
 */

namespace Cortex\Foundation\Overrides\Illuminate\Routing;

use Illuminate\Routing\UrlGenerator as BaseUrlGenerator;
use Illuminate\Routing\Exceptions\UrlGenerationException;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UrlGenerator extends BaseUrlGenerator
{
    /**
     * The backend URI.
     *
     * @var string
     */
    protected $backendUri = 'backend';

    /**
     * Set the backend URI.
     *
     * @param string $backendUri
     *
     * @return void
     */
    public function setBackendUri($backendUri)
    {
        $this->backendUri = $backendUri;
    }

    /**
     * Generate an absolute URL to the given admin path.
     *
     * @param string $path
     * @param array  $parameters
     * @param bool   $secure
     *
     * @return string
     */
    public function toBackend($path, array $parameters = [], $secure = null)
    {
        return $this->to("{$this->backendUri}/{$path}", $parameters, $secure);
    }

    /**
     * Generate a absolute URL to the given path.
     *
     * @param string    $path
     * @param mixed     $extra
     * @param bool|null $secure
     *
     * @return string
     */
    public function to($path, $extra = [], $secure = null)
    {
        return config('rinvex.cortex.route.locale_prefix') ? LaravelLocalization::localizeURL(parent::to($path, $extra, $secure)) : parent::to($path, $extra, $secure);
    }

    /**
     * {@inheritdoc}
     */
    protected function addQueryString($uri, array $parameters)
    {
        // If the URI has a fragment, we will move it to the end of the URI since it will
        // need to come after any query string that may be added to the URL else it is
        // not going to be available. We will remove it then append it back on here.
        if (! is_null($fragment = parse_url($uri, PHP_URL_FRAGMENT))) {
            $uri = preg_replace('/#.*/', '', $uri);
        }

        // Add trailing slash to URL before the query string
        $uri .= '/'.$this->getRouteQueryString($parameters);

        return is_null($fragment) ? $uri : $uri."#{$fragment}";
    }

    /**
     * {@inheritdoc}
     */
    public function previous($fallback = false)
    {
        return ($previousUrl = $this->request->input('previous_url')) ? $this->to($previousUrl) : parent::previous($fallback);
    }

    /**
     * {@inheritdoc}
     */
    protected function toRoute($route, $parameters, $absolute)
    {
        $parameters = $this->formatParameters($parameters);

        // Bind {locale} route parameter
        if (config('rinvex.cortex.route.locale_prefix') && ! isset($parameters['locale'])) {
            $parameters['locale'] = LaravelLocalization::getCurrentLocale();
        }

        $domain = $this->getRouteDomain($route, $parameters);

        $uri = $this->addQueryString($this->trimUrl(
            $root = $this->replaceRoot($route, $domain, $parameters),
            $this->replaceRouteParameters($route->uri(), $parameters)
        ), $parameters);

        if (preg_match('/\{.*?\}/', $uri)) {
            throw UrlGenerationException::forMissingParameters($route);
        }

        $uri = strtr(rawurlencode($uri), $this->dontEncode);

        return $absolute ? $uri : '/'.ltrim(str_replace($root, '', $uri), '/');
    }
}
