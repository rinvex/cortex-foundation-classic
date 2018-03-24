<?php

declare(strict_types=1);

namespace Cortex\Foundation\Importers;

use Exception;
use Maatwebsite\Excel\Files\ImportHandler;
use Maatwebsite\Excel\Collections\CellCollection;

class DefaultImporterHandler implements ImportHandler
{
    public function handle($importer)
    {
        $failed = collect();
        $created = collect();
        $updated = collect();

        $importer->each(function(CellCollection $row) use($importer, &$failed, &$created, &$updated) {
            $fillable = $row->intersectByKeys(array_flip(app($importer->config['resource'])->getFillable()))->toArray();

            try {
                $record = tap(app($importer->config['resource'])->firstOrNew($fillable), function ($instance) {
                    $instance->save();
                });

                $record->wasRecentlyCreated
                    ? $created->push($record->{$importer->config['name']})
                    : $updated->push($record->{$importer->config['name']});
            } catch (Exception $e) {
                $failed->push($record->{$importer->config['name']});
            }
        });

        ! $importer->config['log'] || activity()
            ->performedOn(app($importer->config['resource']))
            ->withProperties([
                    'count' => ['created' => $created->count(), 'updated' => $updated->count(), 'failed' => $failed->count()],
                    'names' => ['created' => $created, 'updated' => $updated, 'failed' => $failed]]
            )
            ->log('imported');
    }
}
