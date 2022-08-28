<?php

declare(strict_types=1);

namespace Cortex\Foundation\Verifiers;

use Illuminate\Validation\DatabasePresenceVerifier;

class EloquentPresenceVerifier extends DatabasePresenceVerifier
{
    /**
     * Count the number of objects in a collection having the given value.
     *
     * @param string      $model
     * @param string      $column
     * @param string      $value
     * @param int|null    $excludeId
     * @param string|null $idColumn
     * @param array       $extra
     *
     * @return int
     */
    public function getCount($model, $column, $value, $excludeId = null, $idColumn = null, array $extra = [])
    {
        $query = $this->model($model)->where($column, '=', $value);

        if (! is_null($excludeId) && $excludeId !== 'NULL') {
            $query->where($idColumn ?: 'id', '<>', $excludeId);
        }

        return $this->addConditions($query, $extra)->count();
    }

    /**
     * Count the number of objects in a collection with the given values.
     *
     * @param string $model
     * @param string $column
     * @param array  $values
     * @param array  $extra
     *
     * @return int
     */
    public function getMultiCount($model, $column, array $values, array $extra = [])
    {
        $query = $this->model($model)->whereIn($column, $values);

        return $this->addConditions($query, $extra)->distinct()->count($column);
    }

    /**
     * Get model eloquent builder.
     *
     * @param $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function model($model)
    {
        return $model->setConnection($this->connection)->useWritePdo();
    }
}
