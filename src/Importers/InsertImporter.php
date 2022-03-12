<?php

declare(strict_types=1);

namespace Cortex\Foundation\Importers;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;

class InsertImporter implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, ShouldQueue
{
    use Importable;
    use RemembersRowNumber;
    use RemembersChunkOffset;

    protected Model $model;

    protected int $chunkSize;

    protected int $batchSize;

    public function model(array $row)
    {
        return $this->model->create($row);
    }

    public function chunkSize(): int
    {
        return $this->chunkSize ?: config('cortex.foundation.datatables.chunk_size');
    }

    public function batchSize(): int
    {
        return $this->batchSize ?: config('cortex.foundation.datatables.batch_size');
    }

    public function withModel(Model $resource)
    {
        $this->model = $resource;

        return $this;
    }

    public function withChunk(int $chunkSize)
    {
        $this->chunkSize = $chunkSize;

        return $this;
    }

    public function withBatch(int $batchSize)
    {
        $this->batchSize = $batchSize;

        return $this;
    }
}
