<?php

declare(strict_types=1);

namespace Cortex\Foundation\Exceptions;

use Throwable;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
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
        //
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        $accessarea = Str::before(Route::currentRouteName(), '.');

        if ($e instanceof TokenMismatchException) {
            return intend([
                'back' => true,
                'with' => ['warning' => trans('cortex/foundation::messages.token_mismatch')],
            ]);
        } elseif ($e instanceof WatsonValidationException) {
            return intend([
                'back' => true,
                'withInput' => $request->all(),
                'withErrors' => $e->errors(),
            ]);
        } elseif ($e instanceof ValidationException) {
            return intend([
                'back' => true,
                'withInput' => $request->all(),
                'withErrors' => $e->errors(),
            ]);
        } elseif ($e instanceof GenericException) {
            return intend([
                'url' => $e->getRedirection() ?? route("{$accessarea}.home"),
                'withInput' => $e->getInputs() ?? $request->all(),
                'with' => ['warning' => $e->getMessage()],
            ]);
        } elseif ($e instanceof AuthorizationException) {
            return intend([
                'url' => in_array($accessarea, ['tenantarea', 'managerarea']) ? route('tenantarea.home') : route('frontarea.home'),
                'with' => ['warning' => $e->getMessage()],
            ]);
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
                        'with' => ['warning' => $e->getMessage()],
                    ]);
                } catch (Exception $e) {
                }
            }

            return $this->prepareResponse($request, $e);
        } elseif ($e instanceof ModelNotFoundException) {
            $model = $e->getModel();
            $single = mb_strtolower(mb_substr($model, mb_strrpos($model, '\\') + 1));
            $plural = Str::plural($single);

            return intend([
                'url' => $model ? route("{$accessarea}.{$plural}.index") : route("{$accessarea}.home"),
                'with' => ['warning' => trans('cortex/foundation::messages.resource_not_found', ['resource' => $single, 'identifier' => $request->route($single)])],
            ]);
        } elseif ($e instanceof ThrottleRequestsException) {
            return intend([
                'back' => true,
                'withInput' => $request->all(),
                'with' => ['warning' => $e->getMessage()],
            ]);
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
            'url' => route($request->route('accessarea').'.login'),
            'with' => ['warning' => trans('cortex/foundation::messages.session_required')],
        ]);
    }
}
