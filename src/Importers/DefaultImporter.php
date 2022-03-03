<?php

declare(strict_types=1);

namespace Cortex\Foundation\Importers;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DefaultImporter implements ToModel, WithHeadingRow
{
    use Importable;

    protected Model $model;

    public function of(Model $resource)
    {
        $this->model = $resource;

        return $this;
    }

    public function model(array $row)
    {
        return $this->model->create($row);
    }
}
