<?php

declare(strict_types=1);

namespace Cortex\Foundation\Verifiers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Database\ConnectionResolverInterface;

class EloquentPresenceVerifier extends DatabasePresenceVerifier
{
    /**
     * The eloquent model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Create a new eloquent presence verifier instance.
     *
     * @param \Illuminate\Database\ConnectionResolverInterface $db
     * @param \Illuminate\Database\Eloquent\Model              $model
     *
     * @return void
     */
    public function __construct(ConnectionResolverInterface $db, Model $model)
    {
        parent::__construct($db);

        $this->model = $model;
    }

    /**
     * Count the number of objects in a collection having the given value.
     *
     * @param  string      $collection
     * @param  string      $column
     * @param  string      $value
     * @param  int|null    $excludeId
     * @param  string|null $idColumn
     * @param  array       $extra
     *
     * @return int
     */
    public function getCount($collection, $column, $value, $excludeId = null, $idColumn = null, array $extra = [])
    {
        $query = $this->model($collection)->where($column, '=', $value);

        if (! is_null($excludeId) && $excludeId !== 'NULL') {
            $query->where($idColumn ?: 'id', '<>', $excludeId);
        }

        return $this->addConditions($query, $extra)->count();
    }

    /**
     * Count the number of objects in a collection with the given values.
     *
     * @param  string $collection
     * @param  string $column
     * @param  array  $values
     * @param  array  $extra
     *
     * @return int
     */
    public function getMultiCount($collection, $column, array $values, array $extra = [])
    {
        $query = $this->model($collection)->whereIn($column, $values);

        return $this->addConditions($query, $extra)->count();
    }

    /**
     * @param $collection
     *
     * @return Model
     */
    private function model($collection)
    {
        return $this->model->setConnection($this->connection)->setTable($collection)->useWritePdo();
    }
}
