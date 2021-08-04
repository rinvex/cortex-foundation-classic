<?php

declare(strict_types=1);

namespace Cortex\Foundation\Traits;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Cortex\Foundation\Exceptions\ModelNotFoundForAccessareaException;

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
            $model->accessareas()->detach();
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
     * Override the default findOrFail method so that we can re-throw
     * a more useful exception. Otherwise it can be very confusing
     * why queries don't work because of accessarea scoping issues.
     *
     * @param mixed $id
     * @param array $columns
     *
     * @throws \Cortex\Foundation\Exceptions\ModelNotFoundForAccessareaException
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     */
    public static function findOrFail($id, $columns = ['*'])
    {
        try {
            return static::query()->findOrFail($id, $columns);
        } catch (ModelNotFoundException $exception) {
            // If it DOES exist, just not for this accessarea, throw a nicer exception
            if (! is_null(static::forAllAccessareas()->find($id, $columns))) {
                throw (new ModelNotFoundForAccessareaException())->setModel(static::class, [$id]);
            }

            throw $exception;
        }
    }

    /**
     * Scope query with all the given accessareas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param mixed                                 $accessareas
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAllAccessareas(Builder $builder, $accessareas): Builder
    {
        $accessareas = $this->prepareAccessareaIds($accessareas);

        collect($accessareas)->each(function ($accessarea) use ($builder) {
            $builder->whereHas('accessareas', function (Builder $builder) use ($accessarea) {
                return $builder->where('id', $accessarea);
            });
        });

        return $builder;
    }

    /**
     * Scope query with any of the given accessareas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param mixed                                 $accessareas
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAnyAccessareas(Builder $builder, $accessareas): Builder
    {
        $accessareas = $this->prepareAccessareaIds($accessareas);

        return $builder->whereHas('accessareas', function (Builder $builder) use ($accessareas) {
            $builder->whereIn('id', $accessareas);
        });
    }

    /**
     * Scope query with any of the given accessareas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param mixed                                 $accessareas
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAccessareas(Builder $builder, $accessareas): Builder
    {
        return static::scopeWithAnyAccessareas($builder, $accessareas);
    }

    /**
     * Scope query without any of the given accessareas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param mixed                                 $accessareas
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutAccessareas(Builder $builder, $accessareas): Builder
    {
        $accessareas = $this->prepareAccessareaIds($accessareas);

        return $builder->whereDoesntHave('accessareas', function (Builder $builder) use ($accessareas) {
            $builder->whereIn('id', $accessareas);
        });
    }

    /**
     * Scope query without any accessareas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutAnyAccessareas(Builder $builder): Builder
    {
        return $builder->doesntHave('accessareas');
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
     * Determine if the model has any the given accessareas.
     *
     * @param mixed $accessareas
     *
     * @return bool
     */
    public function hasAnyAccessareas($accessareas): bool
    {
        return static::hasAccessareas($accessareas);
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
