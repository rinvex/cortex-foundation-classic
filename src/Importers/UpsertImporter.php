<?php

declare(strict_types=1);

namespace Cortex\Foundation\Importers;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\RemembersChunkOffset;

class UpsertImporter implements ToModel, WithHeadingRow, WithBatchInserts, WithUpserts, WithUpsertColumns, WithChunkReading, ShouldQueue
{
    use Importable;
    use RemembersRowNumber;
    use RemembersChunkOffset;

    protected Model $model;

    protected int $chunkSize;

    protected int $batchSize;

    protected array $uniqueBy;

    protected array $upsertColumns;

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

    public function uniqueBy()
    {
        return $this->uniqueBy;
    }

    public function upsertColumns()
    {
        return $this->upsertColumns ?? null;
    }

    public function withUniqueBy(array $uniqueBy)
    {
        $this->uniqueBy = $uniqueBy;

        return $this;
    }

    public function withUpsertColumns(array $upsertColumns)
    {
        $this->upsertColumns = $upsertColumns;

        return $this;
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
