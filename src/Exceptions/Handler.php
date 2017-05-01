<?php

declare(strict_types=1);

namespace Cortex\Foundation\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Rinvex\Fort\Exceptions\AuthorizationException;
use Rinvex\Fort\Exceptions\InvalidPersistenceException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

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
        if ($exception instanceof InvalidPersistenceException) {
            return intend([
                'url' => route('frontend.auth.login'),
                'with' => ['warning' => trans('auth.session.expired')],
            ], 401);
        } elseif ($exception instanceof AuthorizationException) {
            return intend([
                'url' => '/',
                'with' => ['warning' => $exception->getMessage()],
            ], 403);
        } elseif ($exception instanceof ModelNotFoundException) {
            $isBackend = mb_strpos($request->route()->getName(), 'backend') !== false;
            $single = mb_strtolower(mb_substr($exception->getModel(), mb_strrpos($exception->getModel(), '\\') + 1));
            $plural = str_plural($single);

            return intend([
                'url' => $isBackend ? route("backend.{$plural}.index") : route('frontend.home'),
                'with' => ['warning' => trans('cortex/foundation::messages.resource_not_found', ['resource' => $single, 'id' => $request->route()->parameter($single)])],
            ], 404);
        }

        return parent::render($request, $exception);
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
            'url' => route('frontend.auth.login'),
            'with' => ['warning' => trans('auth.session.required')],
        ], 401);
    }
}
