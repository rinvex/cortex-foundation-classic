<?php

declare(strict_types=1);

namespace Cortex\Foundation\Exceptions;

use Exception;
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
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     *
     * @throws \Exception
     *
     * @return void
     */
    public function report(Exception $exception): void
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        $accessarea = str_before(Route::currentRouteName(), '.');

        if ($exception instanceof TokenMismatchException) {
            return intend([
                'back' => true,
                'with' => ['warning' => trans('cortex/foundation::messages.token_mismatch')],
            ]);
        } elseif ($exception instanceof WatsonValidationException) {
            return intend([
                'back' => true,
                'withInput' => $request->all(),
                'withErrors' => $exception->errors(),
            ]);
        } elseif ($exception instanceof ValidationException) {
            return intend([
                'back' => true,
                'withInput' => $request->all(),
                'withErrors' => $exception->errors(),
            ]);
        } elseif ($exception instanceof GenericException) {
            return intend([
                'url' => $exception->getRedirection() ?? route("{$accessarea}.home"),
                'withInput' => $exception->getInputs() ?? $request->all(),
                'with' => ['warning' => $exception->getMessage()],
            ]);
        } elseif ($exception instanceof AuthorizationException) {
            return intend([
                'url' => in_array($accessarea, ['tenantarea', 'managerarea']) ? route('tenantarea.home') : route('frontarea.home'),
                'with' => ['warning' => $exception->getMessage()],
            ]);
        } elseif ($exception instanceof NotFoundHttpException) {
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
                        'with' => ['warning' => $exception->getMessage()],
                    ]);
                } catch (Exception $exception) {
                }
            }

            return $this->prepareResponse($request, $exception);
        } elseif ($exception instanceof ModelNotFoundException) {
            $model = $exception->getModel();
            $single = mb_strtolower(mb_substr($model, mb_strrpos($model, '\\') + 1));
            $plural = str_plural($single);

            return intend([
                'url' => $model ? route("{$accessarea}.{$plural}.index") : route("{$accessarea}.home"),
                'with' => ['warning' => trans('cortex/foundation::messages.resource_not_found', ['resource' => $single, 'identifier' => $request->route($single)])],
            ]);
        } elseif ($exception instanceof ThrottleRequestsException) {
            return intend([
                'back' => true,
                'withInput' => $request->all(),
                'with' => ['warning' => $exception->getMessage()],
            ]);
        }

        return parent::render($request, $exception);
    }

    /**
     * Render the given HttpException.
     *
     * @param \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpExceptionInterface $exception)
    {
        $status = $exception->getStatusCode();

        if (view()->exists("cortex/foundation::common.errors.{$status}")) {
            return response()->view("cortex/foundation::common.errors.{$status}", ['exception' => $exception], $status, $exception->getHeaders());
        }

        return parent::renderHttpException($exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param \Illuminate\Http\Request                 $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     *
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Remember current URL for later redirect
        session()->put('url.intended', url()->current());

        return intend([
            'url' => route($request->route('accessarea').'.login'),
            'with' => ['warning' => trans('cortex/foundation::messages.session_required')],
        ]);
    }
}
