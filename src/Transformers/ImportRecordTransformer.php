<?php

declare(strict_types=1);

namespace Cortex\Foundation\Transformers;

use Rinvex\Support\Traits\Escaper;
use League\Fractal\TransformerAbstract;
use Cortex\Foundation\Models\ImportRecord;

class ImportRecordTransformer extends TransformerAbstract
{
    use Escaper;

    /**
     * @return array
     */
    public function transform(ImportRecord $importRecord): array
    {
        return $this->escape([
            'id' => (int) $importRecord->getKey(),
            'resource' => (string) $importRecord->resource,
            'data' => (string) json_encode($importRecord->data),
            'status' => (string) $importRecord->status,
            'created_at' => (string) $importRecord->created_at,
            'updated_at' => (string) $importRecord->updated_at,
        ]);
    }
}
