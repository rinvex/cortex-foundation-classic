<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Cortex\Foundation\Support\DfsToken;

if (! function_exists('dfs_field')) {
    /**
     * Generate a DFS token form field.
     *
     * @return \Illuminate\Support\HtmlString
     */
    function dfs_field()
    {
        return new HtmlString('<input type="hidden" name="_dfs_token" value="'.dfs_token().'">');
    }
}

if (! function_exists('dfs_token')) {
    /**
     * Get the DFS token value.
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    function dfs_token()
    {
        return app(DfsToken::class)->token();
    }
}

if (! function_exists('extract_title')) {
    /**
     * Extract page title from breadcrumbs.
     *
     * @param mixed $breadcrumbs
     *
     * @return string
     */
    function extract_title($breadcrumbs, string $separator = ' » ')
    {
        return Str::afterLast(preg_replace('/[\n\r\s]+/', ' ', strip_tags(Str::replaceLast($separator, '', str_replace('</li>', $separator, (string) $breadcrumbs)))), $separator)." {$separator} ".config('app.name');
    }
}

if (! function_exists('route_prefix')) {
    /**
     * Return route prefix.
     *
     * @param mixed $accessarea
     *
     * @return string
     */
    function route_prefix($accessarea)
    {
        $prefix = app('accessareas')->firstWhere('slug', $accessarea)?->prefix;

        return config('cortex.foundation.route.locale_prefix') ? "{locale}/{$prefix}" : $prefix;
    }
}

if (! function_exists('intend')) {
    /**
     * Return redirect response.
     *
     * @param array    $arguments
     * @param int|null $status
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    function intend(array $arguments, int $status = null)
    {
        if (request()->expectsJson()) {
            $messages = collect($arguments['with'] ?? []);
            $errors = collect($arguments['withErrors'] ?? []);

            return $errors->isNotEmpty() ?
                response()->json([$errors->flatten()->first() ?: 'Error'], $status ?: 422) :
                response()->json([$messages->flatten()->first() ?: 'OK'], $status ?: 200);
        }

        $redirect = redirect(Arr::pull($arguments, 'url'), in_array($status, [201, 301, 302, 303, 307, 308]) ? $status : 302);

        foreach ($arguments as $key => $value) {
            $redirect = in_array($key, ['home', 'back']) ? $redirect->{$key}() : $redirect->{$key}($value);
        }

        return $redirect;
    }
}

if (! function_exists('route_domains')) {
    /**
     * Return route domains array.
     *
     * @param string|null $accessarea
     *
     * @return array
     */
    function route_domains(string $accessarea = null): array
    {
        static $cachedDomains = null;

        if (isset($cachedDomains[$accessarea])) {
            return $cachedDomains[$accessarea];
        }

        $routeDomains = $accessarea ? collect(config('app.domains'))->filter(fn ($accessareas) => in_array($accessarea, $accessareas))->keys() : collect(config('app.domains'))->keys();

        if (app()->has('request.tenant') && app('request.tenant') && in_array($accessarea, ['managerarea', 'tenantarea'])) {
            $routeDomains = $routeDomains->map(fn ($routeDomain) => app('request.tenant')->slug.'.'.$routeDomain);

            if (! empty(app('request.tenant')->domain)) {
                $routeDomains->prepend(app('request.tenant')->domain);
            }
        }

        return $cachedDomains[$accessarea] = $routeDomains->toArray();
    }
}

if (! function_exists('default_route_domains')) {
    /**
     * Return default route domains array.
     *
     * @return array
     */
    function default_route_domains(): array
    {
        $routeDomains = [];

        app('accessareas')->each(function ($accessarea) use (&$routeDomains) {
            $routeDomains[$accessarea->slug] = get_str_contains(request()->getHost(), $routeDomain = route_domains($accessarea->slug)) ?: $routeDomain[0] ?? route_domains('frontarea')[0];
        });

        return $routeDomains;
    }
}

if (! function_exists('route_pattern')) {
    /**
     * Return route pattern.
     *
     * @param string|null $accessarea
     *
     * @return string
     */
    function route_pattern(string $accessarea = null): string
    {
        $routeDomainsPattern = implode('|', array_map('preg_quote', route_domains($accessarea)));

        return "^($routeDomainsPattern)$";
    }
}
