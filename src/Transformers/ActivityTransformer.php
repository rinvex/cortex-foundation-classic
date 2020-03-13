<?php

declare(strict_types=1);

namespace Cortex\Foundation\Transformers;

use Illuminate\Support\Str;
use Cortex\Foundation\Models\Log;
use Rinvex\Support\Traits\Escaper;
use Illuminate\Support\Facades\Route;
use League\Fractal\TransformerAbstract;

class ActivityTransformer extends TransformerAbstract
{
    use Escaper;

    /**
     * @return array
     */
    public function transform(Log $log): array
    {
        $subject = $log->subject_type;
        $subjects = Str::plural($subject);
        $route = Route::has("adminarea.{$subjects}.edit") ? route("adminarea.{$subjects}.edit", [$subject => $log->subject]) : null;

        if ($log->subject) {
            $subjectName = ucfirst($subject).': '.($log->subject->username ?? $log->subject->name ?? $log->subject->name);
        } else {
            $subjectName = ucfirst($subject).': Not Found!';
        }

        return $this->escape([
            'id' => (int) $log->getKey(),
            'description' => (string) $log->description,
            'subject' => $subjectName,
            'subject_route' => $route,
            'properties' => (object) $log->properties,
            'created_at' => (string) $log->created_at,
        ]);
    }
}
