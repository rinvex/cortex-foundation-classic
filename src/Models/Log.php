<?php

declare(strict_types=1);

namespace Cortex\Foundation\Models;

use Watson\Validating\ValidatingTrait;
use Rinvex\Cacheable\CacheableEloquent;
use Spatie\Activitylog\Models\Activity;

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
    protected $observables = [
        'validating',
        'validated',
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
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('cortex.foundation.tables.activity_log'));
        $this->setRules([
            'log_name' => 'required|string|max:150',
            'description' => 'nullable|string|max:10000',
            'subject_id' => 'nullable|integer',
            'subject_type' => 'nullable|string|max:150',
            'causer_id' => 'nullable|integer',
            'causer_type' => 'nullable|string|max:150',
        ]);
    }
}
