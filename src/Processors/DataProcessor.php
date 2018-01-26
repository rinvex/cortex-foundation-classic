<?php

declare(strict_types=1);

namespace Cortex\Foundation\Processors;

use Yajra\DataTables\Processors\DataProcessor as BaseDataProcessor;

class DataProcessor extends BaseDataProcessor
{
    /**
     * Escape all values of row.
     *
     * @param array $row
     * @return array
     */
    protected function escapeRow(array $row)
    {
        $arrayDot = array_filter(array_dot($row));

        foreach ($arrayDot as $key => $value) {
            if (! in_array($key, $this->rawColumns) && is_string($value)) {
                $arrayDot[$key] = e($value);
            }
        }

        foreach ($arrayDot as $key => $value) {
            array_set($row, $key, $value);
        }

        return $row;
    }
}
