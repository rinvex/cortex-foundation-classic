<?php

declare(strict_types=1);

namespace Cortex\Foundation\Models;

use Watson\Validating\ValidatingTrait;
use Rinvex\Cacheable\CacheableEloquent;
use Spatie\Activitylog\Models\Activity;

/**
 * Cortex\Foundation\Models\Log.
 *
 * @property int                                                $id
 * @property string                                             $log_name
 * @property string                                             $description
 * @property int|null                                           $subject_id
 * @property string|null                                        $subject_type
 * @property int|null                                           $causer_id
 * @property string|null                                        $causer_type
 * @property \Illuminate\Support\Collection                     $properties
 * @property \Carbon\Carbon                                     $created_at
 * @property \Carbon\Carbon                                     $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $causer
 * @property-read mixed                                         $changes
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $subject
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Activitylog\Models\Activity causedBy(\Illuminate\Database\Eloquent\Model $causer)
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Activitylog\Models\Activity forSubject(\Illuminate\Database\Eloquent\Model $subject)
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Activitylog\Models\Activity inLog($logNames)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Log whereCauserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Log whereCauserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Log whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Log whereLogName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Log whereProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Log whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Log whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Cortex\Foundation\Models\Log whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Log extends Activity
{
    use ValidatingTrait;
    use CacheableEloquent;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'log_name',
        'description',
        'subject_id',
        'subject_type',
        'causer_id',
        'causer_type',
        'properties',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'properties' => 'collection',
        'log_name' => 'string',
        'description' => 'string',
        'subject_id' => 'integer',
        'subject_type' => 'string',
        'causer_id' => 'integer',
        'causer_type' => 'string',
    ];

    /**
     * {@inheritdoc}
     */
    protected $observables = [
        'validating',
        'validated',
    ];

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [
        'log_name' => 'required|string|max:150',
        'description' => 'nullable|string|max:10000',
        'subject_id' => 'nullable|integer',
        'subject_type' => 'nullable|string|max:150',
        'causer_id' => 'nullable|integer',
        'causer_type' => 'nullable|string|max:150',
    ];

    /**
     * Whether the model should throw a
     * ValidationException if it fails validation.
     *
     * @var bool
     */
    protected $throwValidationExceptions = true;

    /**
     * Create a new Log model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('cortex.foundation.tables.activity_log'));
    }
}
