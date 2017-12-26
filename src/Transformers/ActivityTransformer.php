<?php

declare(strict_types=1);

namespace Cortex\Foundation\Transformers;

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
        $subject = $log->subject_type;
        $subjects = str_plural($subject);
        $route = Route::has("adminarea.{$subjects}.edit") ? route("adminarea.{$subjects}.edit", [$subject => $log->subject]) : null;

        if ($log->subject) {
            $subjectName = ucfirst($subject).': '.($log->subject->username ?? $log->subject->name ?? $log->subject->title ?? $log->subject->slug);
        } else {
            $subjectName = ucfirst($subject).': Not Found!';
        }

        return [
            'id' => (int) $log->id,
            'description' => (string) $log->description,
            'subject' => $subjectName,
            'subject_route' => $route,
            'properties' => (object) $log->properties,
            'created_at' => (string) $log->created_at,
        ];
    }
}
