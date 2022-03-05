<?php

declare(strict_types=1);

namespace Cortex\Foundation\Transformers;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Yajra\DataTables\Transformers\DataArrayTransformer as BaseDataArrayTransformer;

class DataArrayTransformer extends BaseDataArrayTransformer
{
    /**
     * Transform row column by collection.
     *
     * @override to return headers as db column names, instead of their language titles.
     *
     * @param array                          $row
     * @param \Illuminate\Support\Collection $columns
     * @param string                         $type
     *
     * @return array
     */
    protected function buildColumnByCollection(array $row, Collection $columns, $type = 'printable')
    {
        $results = [];

        foreach ($columns->all() as $column) {
            if ($column[$type] && in_array($column['name'], request()->get('visible_columns', []))) {
                $title = $column['name'];
                $data = Arr::get($row, $column['data']);

                if ($type === 'exportable') {
                    $title = $this->decodeContent($title);
                    $dataType = gettype($data);
                    $data = $this->decodeContent($data);
                    settype($data, $dataType);
                }

                $results[$title] = $data;
            }
        }

        return $results;
    }
}
