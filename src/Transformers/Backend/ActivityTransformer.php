<?php

declare(strict_types=1);

namespace Cortex\Foundation\Transformers\Backend;

use Cortex\Foundation\Models\Log;
use League\Fractal\TransformerAbstract;

class ActivityTransformer extends TransformerAbstract
{
    /**
     * @return array
     */
    public function transform(Log $log)
    {
        $route = '';
        $class = explode('\\', get_class($log->subject));

        switch ($subject = end($class)) {
            case 'Attribute':
                $route = route('backend.attributes.edit', ['attribute' => $log->subject]);
        }

        return [
            'id' => (int) $log->id,
            'description' => (string) $log->description,
            'subject' => $subject.': '.$log->subject->name,
            'subject_route' => $route,
            'properties' => (object) $log->properties,
            'created_at' => (string) $log->created_at,
        ];
    }
}
