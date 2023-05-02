<?php

declare(strict_types=1);

namespace Cortex\Foundation\Models;

use Rinvex\Tags\Traits\Taggable;
use Spatie\Sluggable\SlugOptions;
use Rinvex\Support\Traits\HasSlug;
use Spatie\Activitylog\LogOptions;
use Rinvex\Support\Traits\Macroable;
use Cortex\Foundation\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Support\Traits\HashidsTrait;
use Illuminate\Database\Eloquent\Builder;
use Rinvex\Support\Traits\HasTranslations;
use Rinvex\Support\Traits\ValidatingTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cortex\Foundation\Events\AccessareaCreated;
use Cortex\Foundation\Events\AccessareaDeleted;
use Cortex\Foundation\Events\AccessareaUpdated;
use Cortex\Foundation\Events\AccessareaRestored;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Cortex\Foundation\Exceptions\ProtectedResourceException;

/**
 * Cortex\Foundation\Models\Accessarea.
 *
 * @property int                 $id
 * @property string              $slug
 * @property array               $title
 * @property array               $description
 * @property bool                $is_active
 * @property bool                $is_scoped
 * @property bool                $is_obscured
 * @property bool                $is_indexable
 * @property bool                $is_protected
 * @property bool                $prefix
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea isActive()
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea isScoped()
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea isObscured()
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea isIndexable()
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea isProtected()
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea whereIsScoped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea whereIsObscured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea whereIsIndexable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea whereIsProtected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea whereUrlPrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Accessarea whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Accessarea extends Model
{
    use HasSlug;
    use Taggable;
    use Auditable;
    use Macroable;
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;
    use HashidsTrait;
    use HasTranslations;
    use ValidatingTrait;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'slug',
        'name',
        'description',
        'is_active',
        'is_scoped',
        'is_obscured',
        'is_indexable',
        'is_protected',
        'prefix',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'slug' => 'string',
        'is_active' => 'boolean',
        'is_scoped' => 'boolean',
        'is_obscured' => 'boolean',
        'is_indexable' => 'boolean',
        'is_protected' => 'boolean',
        'prefix' => 'string',
        'deleted_at' => 'datetime',
    ];

    /**
     * {@inheritdoc}
     */
    protected $observables = [
        'validating',
        'validated',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [
        'name',
        'description',
    ];

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Whether the model should throw a
     * ValidationException if it fails validation.
     *
     * @var bool
     */
    protected $throwValidationExceptions = true;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => AccessareaCreated::class,
        'updated' => AccessareaUpdated::class,
        'deleted' => AccessareaDeleted::class,
        'restored' => AccessareaRestored::class,
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('cortex.accessareas.tables.accessareas'));
        $this->mergeRules([
            'slug' => 'required|alpha_dash|max:150|unique:'.config('cortex.accessareas.models.accessarea').',slug',
            'name' => 'required|string|strip_tags|max:150',
            'description' => 'nullable|string|max:32768',
            'is_active' => 'sometimes|boolean',
            'is_scoped' => 'sometimes|boolean',
            'is_obscured' => 'sometimes|boolean',
            'is_indexable' => 'sometimes|boolean',
            'is_protected' => 'sometimes|boolean',
            'prefix' => 'nullable|alpha_dash|max:150|unique:'.config('cortex.accessareas.models.accessarea').',prefix',
        ]);

        parent::__construct($attributes);
    }

    /**
     * Get all attached models of the given class to the accessareas.
     *
     * @param string $class
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function entries(string $class): MorphToMany
    {
        return $this->morphedByMany($class, 'accessible', config('cortex.accessareas.tables.accessibles'), 'accessarea_id', 'accessible_id', 'id', 'id');
    }

    /**
     * Set sensible Activity Log Options.
     *
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                         ->logFillable()
                         ->logOnlyDirty()
                         ->dontSubmitEmptyLogs();
    }

    /**
     * Get the options for generating the slug.
     *
     * @return \Spatie\Sluggable\SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->doNotGenerateSlugsOnUpdate()
                          ->generateSlugsFrom('name')
                          ->saveSlugsTo('slug');
    }

    /**
     * Scope a query to only include active accessareas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include scoped accessareas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsScoped(Builder $query)
    {
        return $query->where('is_scoped', true);
    }

    /**
     * Scope a query to only include obscured accessareas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsObscured(Builder $query)
    {
        return $query->where('is_obscured', true);
    }

    /**
     * Scope a query to only include indexable accessareas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsIndexable(Builder $query)
    {
        return $query->where('is_indexable', true);
    }

    /**
     * Scope a query to only include protected accessareas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsProtected(Builder $query)
    {
        return $query->where('is_protected', true);
    }

    /**
     * Activate accessarea.
     *
     * @return $this
     */
    public function activate()
    {
        $this->update(['is_active' => true]);

        return $this;
    }

    /**
     * Deactivate accessarea.
     *
     * @return $this
     */
    public function deactivate()
    {
        $this->update(['is_active' => false]);

        return $this;
    }

    /**
     * Make accessarea scoped.
     *
     * @return $this
     */
    public function makeScoped()
    {
        $this->update(['is_scoped' => true]);

        return $this;
    }

    /**
     * Make accessarea un-scoped.
     *
     * @return $this
     */
    public function makeUnscoped()
    {
        $this->update(['is_scoped' => false]);

        return $this;
    }

    /**
     * Make accessarea obscured.
     *
     * @return $this
     */
    public function makeObscured()
    {
        $this->update(['is_obscured' => true]);

        return $this;
    }

    /**
     * Make accessarea un-obscured.
     *
     * @return $this
     */
    public function makeUnobscured()
    {
        $this->update(['is_obscured' => false]);

        return $this;
    }

    /**
     * Make accessarea indexable.
     *
     * @return $this
     */
    public function makeIndexable()
    {
        $this->update(['is_indexable' => true]);

        return $this;
    }

    /**
     * Make accessarea un-indexable.
     *
     * @return $this
     */
    public function makeUnindexable()
    {
        $this->update(['is_indexable' => false]);

        return $this;
    }

    /**
     * Make accessarea protected.
     *
     * @return $this
     */
    public function protect()
    {
        $this->update(['is_protected' => true]);

        return $this;
    }

    /**
     * Make accessarea unprotected.
     *
     * @return $this
     */
    public function unprotect()
    {
        $this->update(['is_protected' => false]);

        return $this;
    }

    /**
     * Delete the model from the database.
     *
     * @throws \Cortex\Foundation\Exceptions\ProtectedResourceException
     *
     * @return bool|null
     */
    public function delete()
    {
        if ($this->is_protected) {
            // Prevent deleting protected models
            throw new ProtectedResourceException();
        }

        return parent::delete();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
