<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Controllers;

class AuthorizedController extends AuthenticatedController
{
    /**
     * The resource Ability Map.
     *
     * @var array
     */
    protected $resourceAbilityMap = [
        'activities' => 'audit',
        'index' => 'list',
        'logs' => 'audit',
    ];

    /**
     * The resource methods without models.
     *
     * @var array
     */
    protected $resourceMethodsWithoutModels = [
        'import',
    ];

    /**
     * Resource action whitelist.
     * Array of resource actions to skip mapping to abilities automatically.
     *
     * @var array
     */
    protected $resourceActionWhitelist = [];

    /**
     * Create a new authorized controller instance.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __construct()
    {
        parent::__construct();

        // This authorization check, covers the following use cases:
        // 1. Check individual entity permission (entity_type & entity_id, ex: user ID #123)
        // 2. Check entity permission (entity_type, ex: all entities of type user)
        // 3. Check NULL entity_type (ex. access-adminarea)
        // 4. Check owned entity permissions (owned_by)
        // 5. Check entity based on model config value, instead of hardcoded model (supports override)

        if (property_exists(static::class, 'resource')) {
            if ($this->isClassName($this->resource)) {
                $this->authorizeResource($this->resource);
            } elseif ($modelConfig = config($this->resource)) {
                $this->authorizeResource($modelConfig);
            } else {
                $this->authorizeGeneric($this->resource);
            }
        } else {
            // At this stage, sessions still not loaded yet, and `AuthorizationException`
            // depends on sessions to flash redirection error msg, so delegate to a middleware
            // Since Laravel 5.3 controller constructors executed before middleware to be able to append
            // new middleware to the pipeline then all middleware executed together, and sessions started in `StartSession` middleware
            $this->middleware('can:null');
        }
    }
}
