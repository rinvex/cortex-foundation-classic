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

        return [$connection, $this->getValidationModel($model, $table), $idColumn ?? null];
    }

    /**
     * Return the model instance to be used in validation.
     *
     * @param $model \Illuminate\Database\Eloquent\Model
     * @param $table string
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function getValidationModel(Model $model, string $table): Model
    {
        return $model ? ($this->isValidationScoped($model) ? $model : $model->withoutGlobalScopes()) : (new AbstractModel())->setTable($table);
    }

    /**
     * Returns whether the model validation be scoped or not. (Default: true)
     *
     * @param $model \Illuminate\Database\Eloquent\Model
     *
     * @return bool
     */
    protected function isValidationScoped(Model $model): bool
    {
        return isset($model->isValidationScoped) ? $model->isValidationScoped : true;
    }
}
