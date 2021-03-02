<?php

declare(strict_types=1);

namespace Cortex\Foundation\Http\Controllers;

use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AuthorizedController extends AuthenticatedController
{
    use AuthorizesRequests;

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
        'importLogs',
        'import',
        'stash',
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
            // depends on seesions to flash redirection error msg, so delegate to a middleware
            // Since Laravel 5.3 controller constructors executed before middleware to be able to append
            // new middleware to the pipeline then all middleware executed together, and sessions started in `StartSession` middleware
            $this->middleware('can:null');
        }
    }

    /**
     * Authorize a resource action (with model) based on the incoming request.
     *
     * @param string                        $model
     * @param string|null                   $parameter
     * @param array                         $options
     * @param \Illuminate\Http\Request|null $request
     *
     * @throws \ReflectionException
     *
     * @return void
     */
    public function authorizeResource($model, $parameter = null, array $options = [], $request = null): void
    {
        $middleware = [];
        $parameter = $parameter ?: Str::snake(class_basename($model));

        foreach ($this->mapResourceAbilities() as $method => $ability) {
            $modelName = in_array($method, $this->resourceMethodsWithoutModels()) ? app($model)->getMorphClass() : $parameter;

            $middleware["can:{$ability},{$modelName}"][] = $method;
        }

        foreach ($middleware as $middlewareName => $methods) {
            $this->middleware($middlewareName, $options)->only($methods);
        }
    }

    /**
     * Authorize a resource action (without model) based on the incoming request.
     *
     * @param string $resource
     *
     * @throws \ReflectionException
     *
     * @return void
     */
    public function authorizeGeneric($resource): void
    {
        $middleware = [];

        foreach ($this->mapResourceAbilities() as $method => $ability) {
            $middleware["can:{$resource}"][] = $method;
        }

        foreach ($middleware as $middlewareName => $methods) {
            $this->middleware($middlewareName)->only($methods);
        }
    }

    /**
     * Map resource actions to resource abilities.
     *
     * @throws \ReflectionException
     *
     * @return array
     */
    protected function mapResourceAbilities(): array
    {
        // Reflect calling controller
        $controller = new ReflectionClass(static::class);

        // Get public methods and filter magic methods
        $methods = array_filter($controller->getMethods(ReflectionMethod::IS_PUBLIC), function ($item) use ($controller) {
            return $item->class === $controller->name && mb_substr($item->name, 0, 2) !== '__' && ! in_array($item->name, $this->resourceActionWhitelist);
        });

        // Get controller actions
        $actions = array_combine($items = array_map(function ($action) {
            return $action->name;
        }, $methods), $items);

        // Map resource actions to resourse abilities
        array_walk($actions, function ($value, $key) use (&$actions) {
            $actions[$key] = Arr::get($this->resourceAbilityMap(), $key, $value);
        });

        return $actions;
    }

    /**
     * {@inheritdoc}
     */
    protected function resourceAbilityMap(): array
    {
        return array_merge(parent::resourceAbilityMap(), $this->resourceAbilityMap);
    }

    /**
     * {@inheritdoc}
     */
    protected function resourceMethodsWithoutModels()
    {
        return array_merge(parent::resourceMethodsWithoutModels(), $this->resourceMethodsWithoutModels);
    }

    /**
     * Checks if the given string looks like a fully qualified class name.
     *
     * @param string $value
     *
     * @return bool
     */
    protected function isClassName($value)
    {
        return mb_strpos($value, '\\') !== false;
    }
}
