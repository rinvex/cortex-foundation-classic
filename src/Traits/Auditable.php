<?php

declare(strict_types=1);

namespace Cortex\Foundation\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

trait Auditable
{
    /**
     * Register a creating model event with the dispatcher.
     *
     * @param \Closure|string $callback
     *
     * @return void
     */
    abstract public static function creating($callback);

    /**
     * Register an updating model event with the dispatcher.
     *
     * @param \Closure|string $callback
     *
     * @return void
     */
    abstract public static function updating($callback);

    /**
     * Define a polymorphic, inverse one-to-one or many relationship.
     *
     * @param string $name
     * @param string $type
     * @param string $id
     * @param string $ownerKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    abstract public function morphTo($name = null, $type = null, $id = null, $ownerKey = null);

    /**
     * Boot the Auditable trait for the model.
     *
     * @return void
     */
    public static function bootAuditable()
    {
        static::creating(function (Model $model) {
            $model->created_by_id || $model->created_by_id = optional(auth()->guard(request()->route('guard'))->user())->getKey();
            $model->created_by_type || $model->created_by_type = optional(auth()->guard(request()->route('guard'))->user())->getMorphClass();

            $model->updated_by_id || $model->updated_by_id = optional(auth()->guard(request()->route('guard'))->user())->getKey();
            $model->updated_by_type || $model->updated_by_type = optional(auth()->guard(request()->route('guard'))->user())->getMorphClass();
        });

        static::updating(function (Model $model) {
            $model->isDirty('updated_by_id') || $model->updated_by_id = optional(auth()->guard(request()->route('guard'))->user())->getKey();
            $model->isDirty('updated_by_type') || $model->updated_by_type = optional(auth()->guard(request()->route('guard'))->user())->getMorphClass();
        });
    }

    /**
     * Get the owning creator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function creator(): MorphTo
    {
        return $this->morphTo('creator', 'creator_type', 'creator_id', 'id');
    }

    /**
     * Get the owning updater.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function updater(): MorphTo
    {
        return $this->morphTo('updater', 'updater_type', 'updater_id', 'id');
    }

    /**
     * Get audits of the given creator.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfCreator(Builder $builder, Model $user): Builder
    {
        return $builder->where('created_by_type', $user->getMorphClass())->where('created_by_id', $user->getKey());
    }

    /**
     * Get audits of the given updater.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfUpdater(Builder $builder, Model $user): Builder
    {
        return $builder->where('updated_by_type', $user->getMorphClass())->where('updated_by_id', $user->getKey());
    }
}
