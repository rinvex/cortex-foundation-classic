<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Livewire;

use Illuminate\Support\Str;
use Illuminate\Routing\Route;
use Livewire\LifecycleManager;
use Livewire\ImplicitRouteBinding;
use Livewire\Component as BaseComponent;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use function Livewire\str;

abstract class Component extends BaseComponent
{
    use AuthorizesRequests;

    public static function getName()
    {
        $fullName = str(
            collect(explode('.', str_replace(['Http\\Components', '\\\\', '\\', '/'], ['', '\\', '.', '.'], static::class)))
            ->map([Str::class, 'kebab'])
            ->implode('.')
        );

        $accessarea = $fullName->beforeLast('area.')->afterLast('.')->append('area.');

        return (string) $fullName->remove($accessarea)->prepend($accessarea);
    }

    public function __invoke(Container $container, Route $route)
    {
        // With octane and full page components the route is caching the
        // component, so always create a fresh instance.
        $instance = new static();

        // For some reason Octane doesn't play nice with the injected $route.
        // We need to override it here. However, we can't remove the actual
        // param from the method signature as it would break inheritance.
        $route = request()->route();

        try {
            $componentParams = (new ImplicitRouteBinding($container))->resolveAllParameters($route, $instance);
        } catch (ModelNotFoundException $exception) {
            if (method_exists($route, 'getMissing') && $route->getMissing()) {
                return $route->getMissing()(request());
            }

            throw $exception;
        }

        $manager = LifecycleManager::fromInitialInstance($instance)->boot()->initialHydrate()->mount($componentParams)->renderToView();

        if ($instance->redirectTo) {
            return redirect()->response($instance->redirectTo);
        }

        $instance->ensureViewHasValidLivewireLayout($instance->preRenderedView);

        $layout = $instance->preRenderedView->livewireLayout;

        return app('view')->file(__DIR__."/Macros/livewire-view-{$layout['type']}.blade.php", [
            'view' => $layout['view'],
            'params' => $layout['params'],
            'slotOrSection' => $layout['slotOrSection'],
            'manager' => $manager,
        ]);
    }
}
