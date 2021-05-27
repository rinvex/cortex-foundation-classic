<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationMiddlewareBase;

/**
 * Class LocaleSessionRedirect.
 *
 * This middleware is accessed ONLY if there's a match of existing routes,
 * whether localized or not but has already existing definition in route map.
 * It simply fix any wrong localized routes, and has no effect on non-localized.
 *
 * Localized routes with missing {locale} are handled through the exception handler:
 * \Cortex\Foundation\Exceptions\ExceptionHandler::render => instanceof NotFoundHttpException
 */
class LocalizationRedirect extends LaravelLocalizationMiddlewareBase
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // If the URL of the request is in exceptions.
        if ($this->shouldIgnore($request)) {
            return $next($request);
        }

        $urlLocale = $request->route('locale');
        $sessionLocale = session('locale', app('laravellocalization')->getCurrentLocale());

        // {locale} exists in URL & supported
        if (app('laravellocalization')->checkLocaleInSupportedLocales($urlLocale)) {
            session(['locale' => $urlLocale]);

            return $next($request);
        }

        // {locale} exists in URL & NOT supported, get {locale}
        // from session or get default if session does not exist too
        if ($urlLocale && (app('laravellocalization')->checkLocaleInSupportedLocales($sessionLocale) || $sessionLocale = app('laravellocalization')->getCurrentLocale())) {
            $url = route($request->route()->getName(), ['locale' => $sessionLocale]);
            session(['locale' => $sessionLocale]);
            app('session')->reflash();

            return new RedirectResponse($url, 302, ['Vary' => 'Accept-Language']);
        }

        return $next($request);
    }
}
