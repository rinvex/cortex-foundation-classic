<?php

declare(strict_types=1);

namespace Cortex\Foundation\Traits;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Accessible
{
    /**
     * Register a saved model event with the dispatcher.
     *
     * @param \Closure|string $callback
     *
     * @return void
     */
    abstract public static function saved($callback);

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     *
     * @return void
     */
    abstract public static function deleted($callback);

    /**
     * Define a polymorphic many-to-many relationship.
     *
     * @param string $related
     * @param string $name
     * @param string $table
     * @param string $foreignPivotKey
     * @param string $relatedPivotKey
     * @param string $parentKey
     * @param string $relatedKey
     * @param bool   $inverse
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    abstract public function morphToMany(
        $related,
        $name,
        $table = null,
        $foreignPivotKey = null,
        $relatedPivotKey = null,
        $parentKey = null,
        $relatedKey = null,
        $inverse = false
    );

    /**
     * @TODO: refactor to drop accessareas db table.
     * Get all attached accessareas to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function accessareas(): MorphToMany
    {
        return $this->morphToMany(config('cortex.foundation.models.accessarea'), 'accessible', config('cortex.foundation.tables.accessibles'), 'accessible_id', 'accessarea_id')
                    ->withTimestamps();
    }

    /**
     * Attach the given accessareas to the model.
     *
     * @param mixed $accessareas
     *
     * @return void
     */
    public function setAccessareasAttribute($accessareas): void
    {
        static::saved(function (self $model) use ($accessareas) {
            $model->syncAccessareas($accessareas);
        });
    }

    /**
     * Boot the accessible trait for the model.
     *
     * @return void
     */
    public static function bootAccessible()
    {
        if (request()->accessarea() && $accessarea = app('cortex.foundation.accessarea')->where('slug', request()->accessarea())->where('is_active', true)->where('is_scoped', true)->first()) {
            static::addGlobalScope('accessible', function (Builder $builder) use ($accessarea) {
                $builder->whereHas('accessareas', function (Builder $builder) use ($accessarea) {
                    $builder->where($key = $accessarea->getKeyName(), $accessarea->{$key});
                });
            });

            static::saved(function (self $model) use ($accessarea) {
                $model->attachAccessareas($accessarea);
            });
        }

        static::deleted(function (self $model) {
            // Check if this is a soft delete or not by checking if `SoftDeletes::isForceDeleting` method exists
            (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) || $model->accessareas()->detach();
        });
    }

    /**
     * Returns a new query builder without any of the accessarea scopes applied.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function forAllAccessareas()
    {
        return (new static())->newQuery()->withoutGlobalScopes(['accessible']);
    }

    /**
     * Determine if the model has any of the given accessareas.
     *
     * @param mixed $accessareas
     *
     * @return bool
     */
    public function hasAccessareas($accessareas): bool
    {
        $accessareas = $this->prepareAccessareaIds($accessareas);

        return ! $this->accessareas->pluck('id')->intersect($accessareas)->isEmpty();
    }

    /**
     * Determine if the model has all of the given accessareas.
     *
     * @param mixed $accessareas
     *
     * @return bool
     */
    public function hasAllAccessareas($accessareas): bool
    {
        $accessareas = $this->prepareAccessareaIds($accessareas);

        return collect($accessareas)->diff($this->accessareas->pluck('id'))->isEmpty();
    }

    /**
     * Sync model accessareas.
     *
     * @param mixed $accessareas
     * @param bool  $detaching
     *
     * @return $this
     */
    public function syncAccessareas($accessareas, bool $detaching = true)
    {
        // Find accessareas
        $accessareas = $this->prepareAccessareaIds($accessareas);

        // Sync model accessareas
        $this->accessareas()->sync($accessareas, $detaching);

        return $this;
    }

    /**
     * Attach model accessareas.
     *
     * @param mixed $accessareas
     *
     * @return $this
     */
    public function attachAccessareas($accessareas)
    {
        return $this->syncAccessareas($accessareas, false);
    }

    /**
     * Detach model accessareas.
     *
     * @param mixed $accessareas
     *
     * @return $this
     */
    public function detachAccessareas($accessareas = null)
    {
        $accessareas = ! is_null($accessareas) ? $this->prepareAccessareaIds($accessareas) : null;

        // Sync model accessareas
        $this->accessareas()->detach($accessareas);

        return $this;
    }

    /**
     * Prepare accessarea IDs.
     *
     * @param mixed $accessareas
     *
     * @return array
     */
    protected function prepareAccessareaIds($accessareas): array
    {
        // Convert collection to plain array
        if ($accessareas instanceof BaseCollection && is_string($accessareas->first())) {
            $accessareas = $accessareas->toArray();
        }

        // Find accessareas by their ids
        if (is_numeric($accessareas) || (is_array($accessareas) && is_numeric(Arr::first($accessareas)))) {
            return array_map('intval', (array) $accessareas);
        }

        // Find accessareas by their slugs
        if (is_string($accessareas) || (is_array($accessareas) && is_string(Arr::first($accessareas)))) {
            $accessareas = app('cortex.foundation.accessarea')->whereIn('slug', $accessareas)->get()->pluck('id');
        }

        if ($accessareas instanceof Model) {
            return [$accessareas->getKey()];
        }

        if ($accessareas instanceof Collection) {
            return $accessareas->modelKeys();
        }

        if ($accessareas instanceof BaseCollection) {
            return $accessareas->toArray();
        }

        return (array) $accessareas;
    }
}
