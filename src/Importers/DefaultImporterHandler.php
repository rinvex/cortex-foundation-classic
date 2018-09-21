<?php

declare(strict_types=1);

namespace Cortex\Foundation\Importers;

use Maatwebsite\Excel\Files\ExcelFile;
use Maatwebsite\Excel\Collections\CellCollection;

class DefaultImporterHandler
{
    public function handle(ExcelFile $importer)
    {
        $importer->each(function (CellCollection $row) use ($importer) {
            app('cortex.foundation.import_record')->create([
                'resource' => app($importer->config['resource'])->getMorphClass(),
                'data' => $row,
            ]);
        });
    }
}
