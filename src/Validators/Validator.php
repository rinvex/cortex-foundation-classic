<?php

declare(strict_types=1);

namespace Cortex\Foundation\Validators;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Cortex\Foundation\Models\AbstractModel;
use Illuminate\Validation\Validator as BaseValidator;

/**
 * @override default validator to support the new `EloquentPresenceVerifier`
 */
class Validator extends BaseValidator
{
    /**
     * Parse the connection / table for the unique / exists rules.
     *
     * @param string $table
     *
     * @return array
     */
    public function parseTable($table)
    {
        [$connection, $table] = str_contains($table, '.') ? explode('.', $table, 2) : [null, $table];

        if (str_contains($table, '\\') && class_exists($table) && is_a($table, Model::class, true)) {
            $model = new $table();

            $table = $model->getTable();
            $connection ??= $model->getConnectionName();

            if (str_contains($table, '.') && Str::startsWith($table, $connection)) {
                $connection = null;
            }

            $idColumn = $model->getKeyName();
        }

        return [$connection, $model ?? (new AbstractModel())->setTable($table), $idColumn ?? null];
    }
}
