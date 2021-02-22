<?php

declare(strict_types=1);

namespace Cortex\Foundation\Exceptions;

use Exception;
use Throwable;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Support\Facades\Route;
use Rinvex\Country\CountryLoaderException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Rinvex\University\UniversityLoaderException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Watson\Validating\ValidationException as WatsonValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        GenericException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Throwable $e
     *
     * @throws \Exception
     *
     * @return void
     */
    public function report(Throwable $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable               $e
     *
     * @throws \Throwable
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e)
    {
        $accessarea = $request->getAccessArea();

        if ($e instanceof TokenMismatchException) {
            return intend([
                'back' => true,
                'withErrors' => ['error' => trans('cortex/foundation::messages.token_mismatch')],
            ], $e->getCode());
        } elseif ($e instanceof WatsonValidationException) {
            return intend([
                'back' => true,
                'withInput' => $request->all(),
                'withErrors' => $e->errors(),
            ], $e->getCode());
        } elseif ($e instanceof ValidationException) {
            return intend([
                'back' => true,
                'withInput' => $request->all(),
                'withErrors' => $e->errors(),
            ], $e->getCode());
        } elseif ($e instanceof GenericException) {
            return intend([
                'url' => $e->getRedirection() ?? route("{$accessarea}.home"),
                'withInput' => $e->getInputs() ?? $request->all(),
                'withErrors' => ['error' => $e->getMessage()],
            ], $e->getCode());
        } elseif ($e instanceof AuthorizationException) {
            return intend([
                'url' => in_array($accessarea, ['tenantarea', 'managerarea']) ? route('tenantarea.home') : route('frontarea.home'),
                'withErrors' => ['error' => $e->getMessage()],
            ], $e->getCode());
        } elseif ($e instanceof NotFoundHttpException) {
            // Catch localized routes with missing {locale}
            // and redirect them to the correct localized version
            if (config('cortex.foundation.route.locale_redirect')) {
                $originalUrl = $request->url();

                try {
                    $localizedUrl = app('laravellocalization')->getLocalizedURL(null, $originalUrl);

                    // Will return `NotFoundHttpException` exception if no match found!
                    app('router')->getRoutes()->match(request()->create($localizedUrl));

                    return intend([
                        'url' => $originalUrl !== $localizedUrl ? $localizedUrl : route("{$accessarea}.home"),
                        'withErrors' => ['error' => $e->getMessage()],
                    ], $e->getCode());
                } catch (Exception $e) {
                }
            }

            return $this->prepareResponse($request, $e);
        } elseif ($e instanceof ModelNotFoundException) {
            $model = $e->getModel();
            $single = mb_strtolower(mb_substr($model, mb_strrpos($model, '\\') + 1));
            $plural = Str::plural($single);

            return intend([
                'url' => Route::has("{$accessarea}.{$plural}.index") ? route("{$accessarea}.{$plural}.index") : route("{$accessarea}.home"),
                'withErrors' => ['error' => trans('cortex/foundation::messages.resource_not_found', ['resource' => $single, 'identifier' => $request->route($single)])],
            ], $e->getCode());
        } elseif ($e instanceof UniversityLoaderException || $e instanceof CountryLoaderException) {
            return intend([
                'url' => route("{$accessarea}.home"),
                'withErrors' => ['error' => $e->getMessage()],
            ], $e->getCode());
        } elseif ($e instanceof ThrottleRequestsException) {
            return intend([
                'back' => true,
                'withInput' => $request->all(),
                'withErrors' => ['error' => $e->getMessage()],
            ], $e->getCode());
        }

        return parent::render($request, $e);
    }

    /**
     * Render the given HttpException.
     *
     * @param \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpExceptionInterface $e)
    {
        if (view()->exists($view = $this->getHttpExceptionView($e))) {
            return response()->view($view, ['errors' => new ViewErrorBag(), 'exception' => $e], $e->getStatusCode(), $e->getHeaders());
        }

        return parent::renderHttpException($e);
    }

    /**
     * Get the view used to render HTTP exceptions.
     *
     * @param \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e
     *
     * @return string
     */
    protected function getHttpExceptionView(HttpExceptionInterface $e)
    {
        return "cortex/foundation::common.errors.{$e->getStatusCode()}";
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param \Illuminate\Http\Request                 $request
     * @param \Illuminate\Auth\AuthenticationException $e
     *
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $e)
    {
        // Remember current URL for later redirect
        session()->put('url.intended', url()->current());

        return intend([
            'url' => app()->bound('request.accessarea') ? route(app('request.accessarea').'.cortex.auth.account.login') : route('frontarea.cortex.auth.account.login'),
            'withErrors' => ['error' => trans('cortex/foundation::messages.session_required')],
        ]);
    }
}
