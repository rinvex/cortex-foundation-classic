<?php

declare(strict_types=1);

namespace Cortex\Foundation\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;

class Tab extends Facade
{
    /**
     * Get the tab headers.
     *
     * @return string
     */
    public static function headers(string $service, Model $entity)
    {
        return app()->bound($service) ? app($service)->pluck('header')->map(function ($item) use ($entity) {
            return view(str_replace('{accessarea}', request('accessarea'), $item), ['entity' => $entity])->render();
        })->implode('') : '';
    }

    /**
     * Get the tab panels.
     *
     * @return string
     */
    public static function panels(string $service, Model $entity)
    {
        return app()->bound($service) ? app($service)->pluck('panel')->map(function ($item) use ($entity) {
            return view(str_replace('{accessarea}', request('accessarea'), $item), ['entity' => $entity])->render();
        })->implode('') : '';
    }
}
