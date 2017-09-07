<?php

declare(strict_types=1);

namespace Cortex\Foundation\Transformers\Adminarea;

use Cortex\Foundation\Models\Log;
use Illuminate\Support\Facades\Route;
use League\Fractal\TransformerAbstract;

class LogTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(Log $log)
    {
        $causer_route = '';

        if ($log->causer) {
            $class = explode('\\', get_class($log->causer));
            $singleResource = lower_case(end($class));
            $pluralResource = str_plural(lower_case(end($class)));
            $causer = ucfirst($singleResource).': '.($log->causer->username ?? $log->causer->name ?? $log->causer->title ?? $log->causer->slug);
            $causer_route = Route::has("adminarea.{$pluralResource}.edit") ? route("adminarea.{$pluralResource}.edit", [$singleResource => $log->causer]) : null;
        } else {
            $causer = 'System';
        }

        return [
            'id' => (int) $log->id,
            'description' => (string) $log->description,
            'causer' => $causer,
            'causer_route' => $causer_route,
            'properties' => (object) $log->properties,
            'created_at' => (string) $log->created_at,
        ];
    }
}
