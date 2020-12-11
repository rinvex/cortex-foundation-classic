<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Yajra\DataTables;

use Cortex\Foundation\Processors\DataProcessor;
use Yajra\DataTables\EloquentDataTable as BaseEloquentDataTable;

class EloquentDataTable extends BaseEloquentDataTable
{
    /**
     * Get processed data.
     *
     * @param mixed $results
     * @param bool  $object
     *
     * @return array
     */
    protected function processResults($results, $object = false)
    {
        $processor = new DataProcessor(
            $results,
            $this->getColumnsDefinition(),
            $this->templates,
            $this->request->input('start')
        );

        return $processor->process($object);
    }
}
