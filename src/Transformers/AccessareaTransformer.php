<?php

declare(strict_types=1);

namespace Cortex\Foundation\Transformers;

use Rinvex\Support\Traits\Escaper;
use League\Fractal\TransformerAbstract;
use Cortex\Foundation\Models\Accessarea;

class AccessareaTransformer extends TransformerAbstract
{
    use Escaper;

    /**
     * Transform accessarea model.
     *
     * @param \Cortex\Foundation\Models\Accessarea $accessarea
     *
     * @throws \Exception
     *
     * @return array
     */
    public function transform(Accessarea $accessarea): array
    {
        return $this->escape([
            'id' => (string) $accessarea->getRouteKey(),
            'name' => (string) $accessarea->name,
            'is_active' => (bool) $accessarea->is_active,
            'is_obscured' => (bool) $accessarea->is_obscured,
            'created_at' => (string) $accessarea->created_at,
            'updated_at' => (string) $accessarea->updated_at,
        ]);
    }
}
