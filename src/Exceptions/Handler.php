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

namespace Cortex\Foundation\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Rinvex\Fort\Exceptions\InvalidPersistenceException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Rinvex\Repository\Exceptions\EntityNotFoundException;
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
     * @param  \Exception  $exception
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
                'route'      => 'rinvex.fort.frontend.auth.login',
                'withErrors' => ['rinvex.fort.session.expired' => trans('rinvex/fort::frontend/messages.auth.session.expired')],
            ], 401);
        } elseif ($exception instanceof EntityNotFoundException) {
            $single = strtolower(trim(strrchr($exception->getModel(), '\\'), '\\'));
            $plural = str_plural($single);

            return intend([
                'route'      => 'rinvex.fort.backend.'.$plural.'.index',
                'withErrors' => ['rinvex.fort.'.$single.'.not_found' => trans('rinvex/fort::backend/messages.'.$single.'.not_found', [$single.'Id' => $exception->getId()])],
            ]);
        } elseif ($exception instanceof AuthorizationException) {
            return intend([
                'url'        => '/',
                'withErrors' => ['rinvex.fort.unauthorized' => $exception->getMessage()],
            ], 403);
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
            'route'      => 'rinvex.fort.frontend.auth.login',
            'withErrors' => ['rinvex.fort.session.required' => trans('rinvex/fort::frontend/messages.auth.session.required')],
        ], 401);
    }
}
