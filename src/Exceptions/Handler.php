<?php

declare(strict_types=1);

namespace Cortex\Foundation\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Rinvex\Fort\Exceptions\GenericException;
use Illuminate\Session\TokenMismatchException;
use Rinvex\Fort\Exceptions\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Watson\Validating\ValidationException as WatsonValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     *
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            return intend([
                'back' => true,
                'with' => ['warning' => trans('cortex/foundation::messages.token_mismatch')],
            ], 419);
        } elseif ($exception instanceof WatsonValidationException) {
            return intend([
                'back' => true,
                'withInput' => $request->all(),
                'withErrors' => $exception->errors(),
            ], 400);
        } elseif ($exception instanceof GenericException) {
            return intend([
                'url' => $exception->getRedirection() ?? route('guestarea.home'),
                'withInput' => $exception->getInputs() ?? $request->all(),
                'with' => ['warning' => $exception->getMessage()],
            ], 422);
        } elseif ($exception instanceof AuthorizationException) {
            return intend([
                'url' => '/',
                'with' => ['warning' => $exception->getMessage()],
            ]);
        } elseif ($exception instanceof NotFoundHttpException) {
            // Catch localized routes with missing {locale}
            // and redirect them to the correct localized version
            if (config('cortex.foundation.route.locale_redirect')) {
                $originalUrl = $request->url();
                $localizedUrl = app('laravellocalization')->getLocalizedURL(null, $originalUrl);

                try {
                    // Will return `NotFoundHttpException` exception if no match found!
                    app('router')->getRoutes()->match(request()->create($localizedUrl));

                    return intend([
                        'url' => $originalUrl !== $localizedUrl ? $localizedUrl : route('guestarea.home'),
                        'with' => ['warning' => $exception->getMessage()],
                    ]);
                } catch (Exception $exception) {
                }
            }

            return $this->prepareResponse($request, $exception);
        } elseif ($exception instanceof ModelNotFoundException) {
            $model = str_replace('Contract', '', $exception->getModel());
            $isAdminarea = mb_strpos($request->route()->getName(), 'adminarea') !== false;
            $single = mb_strtolower(mb_substr($model, mb_strrpos($model, '\\') + 1));
            $plural = str_plural($single);

            return intend([
                'url' => $isAdminarea ? route("adminarea.{$plural}.index") : route('guestarea.home'),
                'with' => ['warning' => trans('cortex/foundation::messages.resource_not_found', ['resource' => $single, 'id' => $request->route()->parameter($single)])],
            ], 404);
        }

        return parent::render($request, $exception);
    }

    /**
     * Render the given HttpException.
     *
     * @param \Symfony\Component\HttpKernel\Exception\HttpException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpException $exception)
    {
        $status = $exception->getStatusCode();

        if (view()->exists("cortex/foundation::common.errors.{$status}")) {
            return response()->view("cortex/foundation::common.errors.{$status}", ['exception' => $exception], $status, $exception->getHeaders());
        } else {
            return parent::renderHttpException($exception);
        }
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
        return intend([
            'url' => route('guestarea.auth.login'),
            'with' => ['warning' => trans('cortex/foundation::messages.session_required')],
        ]);
    }
}
