<?php

declare(strict_types=1);

namespace Cortex\Foundation\Overrides\Livewire;

use Livewire\Exceptions\ComponentNotFoundException;
use Livewire\LivewireManager as BaseLivewireManager;

class LivewireManager extends BaseLivewireManager
{
    protected $persistentMiddleware = [
        //\Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        //\Illuminate\Routing\Middleware\SubstituteBindings::class,
        //\App\Http\Middleware\RedirectIfAuthenticated::class,
        //\Illuminate\Auth\Middleware\Authenticate::class,
        //\Illuminate\Auth\Middleware\Authorize::class,
        //\App\Http\Middleware\Authenticate::class,
    ];

    public function getClass($alias)
    {
        $finder = app(LivewireComponentsFinder::class);

        $class = false;

        $class = $class ?: (
            // Let's first check if the user registered the component using:
            // Livewire::component('name', [Livewire component class]);
            // If not, we'll look in the auto-discovery manifest.
            $this->componentAliases[$alias] ?? $finder->find($alias)
        );

        $class = $class ?: (
            // If none of the above worked, our last-ditch effort will be
            // to re-generate the auto-discovery manifest and look again.
            $finder->build()->find($alias)
        );

        throw_unless($class, new ComponentNotFoundException(
            "Unable to find component: [{$alias}]"
        ));

        return $class;
    }

    public function isDefinitelyLivewireRequest()
    {
        $route = request()->route();

        if (! $route) {
            return false;
        }

        // Str::contains(Route::currentRouteName(), $route);
        return $route->named('livewire.message');
    }
}
