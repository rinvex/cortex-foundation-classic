<?php

declare(strict_types=1);

namespace Cortex\Foundation\Models;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Support\Traits\HashidsTrait;

class ImportRecord extends Model
{
    use HashidsTrait;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'resource',
        'data',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'resource' => 'string',
        'data' => 'json',
        'status' => 'nullable|in:init,skip,fail,update,success',
    ];

    /**
     * Create a new Log model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('cortex.foundation.tables.import_records'));
    }
}
