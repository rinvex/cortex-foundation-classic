<?php

declare(strict_types=1);

namespace Cortex\Foundation\Transformers\Adminarea;

use Cortex\Foundation\Models\Log;
use Illuminate\Support\Facades\Route;
use League\Fractal\TransformerAbstract;

class ActivityTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(Log $log)
    {
        $class = explode('\\', get_class($log->subject));
        $subject = lower_case(end($class));
        $subjects = str_plural(lower_case(end($class)));
        $route = Route::has("adminarea.{$subjects}.edit") ? route("adminarea.{$subjects}.edit", [$subject => $log->subject]) : null;

        return [
            'id' => (int) $log->id,
            'description' => (string) $log->description,
            'subject' => ucfirst($subject).': '.($log->subject->username ?? $log->subject->name ?? $log->subject->title ?? $log->subject->slug),
            'subject_route' => $route,
            'properties' => (object) $log->properties,
            'created_at' => (string) $log->created_at,
        ];
    }
}
