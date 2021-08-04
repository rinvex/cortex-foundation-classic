<?php

declare(strict_types=1);

namespace Cortex\Foundation\Providers;

use Illuminate\Routing\Router;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Cortex\Foundation\Http\FormRequest;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Cortex\Foundation\Models\Accessarea;
use Illuminate\Database\Schema\Blueprint;
use Cortex\Foundation\Models\ImportRecord;
use Cortex\Foundation\Models\AbstractModel;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\View\Compilers\BladeCompiler;
use Cortex\Foundation\Http\Middleware\Clockwork;
use Cortex\Foundation\Generators\LangJsGenerator;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Session as SessionFacade;
use Cortex\Foundation\Verifiers\EloquentPresenceVerifier;
use Cortex\Foundation\Http\Middleware\NotificationMiddleware;
use Cortex\Foundation\Overrides\Illuminate\Routing\Redirector;
use Cortex\Foundation\Overrides\Illuminate\Routing\UrlGenerator;
use Cortex\Foundation\Overrides\Barryvdh\Debugbar\DebugbarServiceProvider;
use Cortex\Foundation\Overrides\Mcamara\LaravelLocalization\LaravelLocalization;
use Cortex\Foundation\Overrides\Mariuzzo\LaravelJsLocalization\Commands\LangJsCommand;

class FoundationServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register(): void
    {
        $this->overrideNotificationMiddleware();
        $this->overrideLaravelLocalization();
        $this->overrideUrlGenerator();
        $this->bindPresenceVerifier();
        $this->bindBlueprintMacro();
        $this->overrideRedirector();
        $this->bindBladeCompiler();
        $this->overrideLangJS();

        // Bind eloquent models to IoC container
        $this->registerModels([
            'cortex.foundation.import_record' => ImportRecord::class,
            'cortex.foundation.accessarea' => Accessarea::class,
        ]);

        // Override datatables html builder
        $this->app->bind(\Yajra\DataTables\Html\Builder::class, \Cortex\Foundation\Overrides\Yajra\DataTables\Html\Builder::class);

        // Register dev service providers
        $this->app->environment('production') || $this->app->register(DebugbarServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router, Dispatcher $dispatcher): void
    {
        // Fix the specified key was too long error
        Schema::defaultStringLength(191);

        // Use Pagination bootstrap styles
        Paginator::useBootstrap();

        // Override presence verifier
        $this->app['validator']->setPresenceVerifier($this->app['cortex.foundation.presence.verifier']);

        // Bind route models and constrains
        $router->pattern('locale', '[a-z]{2}');
        $router->pattern('accessarea', '[a-zA-Z0-9-_]+');
        $router->model('media', config('medialibrary.media_model'));
        $router->model('accessarea', config('cortex.foundation.models.accessarea'));

        // Early set application locale globaly
        $this->app['laravellocalization']->setLocale();

        // Map relations
        Relation::morphMap([
            'media' => config('medialibrary.media_model'),
            'accessarea' => config('cortex.foundation.models.accessarea'),
        ]);

        SessionFacade::extend('database', function ($app) {
            $table = $app['config']['session.table'];

            $lifetime = $app['config']['session.lifetime'];
            $connection = $app['config']['session.connection'];

            return new \Cortex\Foundation\Overrides\Illuminate\Session\DatabaseSessionHandler(
                $app['db']->connection($connection),
                $table,
                $lifetime,
                $app
            );
        });

        // Append middleware to the 'web' middleware group
        $this->app->environment('production') || $router->pushMiddlewareToGroup('web', Clockwork::class);

        // Override `FormRequest` container binding
        $this->app->resolving(FormRequest::class, function ($request, $app) {
            $request = FormRequest::createFrom($app['request'], $request);

            $request->setContainer($app)->setRedirector($app->make(Redirector::class));
        });
    }

    /**
     * Override notification middleware.
     *
     * @return void
     */
    protected function overrideNotificationMiddleware(): void
    {
        $this->app->singleton('Cortex\Foundation\Http\Middleware\NotificationMiddleware', function ($app) {
            return new NotificationMiddleware(
                $app['session.store'],
                $app['notification'],
                $app['config']->get('notification.session_key')
            );
        });
    }

    /**
     * Bind blade compiler.
     *
     * @return void
     */
    protected function bindBladeCompiler(): void
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {

            // @alerts('container')
            $bladeCompiler->directive('alerts', function ($container = null) {
                if (strcasecmp('()', $container) === 0) {
                    $container = null;
                }

                return "<?php echo app('notification')->container({$container})->show(); ?>";
            });
        });
    }

    /**
     * Override the Redirector instance.
     *
     * @return void
     */
    protected function overrideRedirector(): void
    {
        $this->app->singleton('redirect', function ($app) {
            $redirector = new Redirector($app['url']);

            // If the session is set on the application instance, we'll inject it into
            // the redirector instance. This allows the redirect responses to allow
            // for the quite convenient "with" methods that flash to the session.
            if (isset($app['session.store'])) {
                $redirector->setSession($app['session.store']);
            }

            return $redirector;
        });
    }

    /**
     * Override the UrlGenerator instance.
     *
     * @return void
     */
    protected function overrideUrlGenerator(): void
    {
        $this->app->singleton('url', function ($app) {
            $routes = $app['router']->getRoutes();

            // The URL generator needs the route collection that exists on the router.
            // Keep in mind this is an object, so we're passing by references here
            // and all the registered routes will be available to the generator.
            $app->instance('routes', $routes);

            $url = new UrlGenerator(
                $routes,
                $app->rebinding(
                    'request',
                    $this->requestRebinder()
                )
            );

            $url->setSessionResolver(function () {
                return $this->app['session'];
            });

            // If the route collection is "rebound", for example, when the routes stay
            // cached for the application, we will need to rebind the routes on the
            // URL generator instance so it has the latest version of the routes.
            $app->rebinding('routes', function ($app, $routes) {
                $app['url']->setRoutes($routes);
            });

            return $url;
        });
    }

    /**
     * Get the URL generator request rebinder.
     *
     * @return \Closure
     */
    protected function requestRebinder()
    {
        return function ($app, $request) {
            $app['url']->setRequest($request);
        };
    }

    /**
     * Override the LaravelLocalization instance.
     *
     * @return void
     */
    protected function overrideLaravelLocalization(): void
    {
        $this->app->singleton('laravellocalization', function () {
            return new LaravelLocalization();
        });
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function overrideLangJS(): void
    {
        // Bind the Laravel JS Localization command into the app IOC.
        $this->app->singleton('localization.js', function () {
            $files = $this->app['files'];
            $messages = $this->app['config']->get('localization-js.messages');
            $generator = new LangJsGenerator($files, $this->app['path.base'].'/resources/lang', $messages);

            return new LangJsCommand($generator);
        });

        // Bind the Laravel JS Localization command into Laravel Artisan.
        $this->commands('localization.js');
    }

    /**
     * Bind presence verifier.
     *
     * @return void
     */
    protected function bindPresenceVerifier(): void
    {
        $this->app->bind('cortex.foundation.presence.verifier', function ($app) {
            return new EloquentPresenceVerifier($app['db'], new $app[AbstractModel::class]());
        });
    }

    /**
     * Bind blueprint macro.
     *
     * @return void
     */
    protected function bindBlueprintMacro(): void
    {
        Blueprint::macro('auditable', function () {
            $this->integer('created_by_id')->unsigned()->after('created_at')->nullable();
            $this->string('created_by_type')->after('created_at')->nullable();
            $this->integer('updated_by_id')->unsigned()->after('updated_at')->nullable();
            $this->string('updated_by_type')->after('updated_at')->nullable();
        });

        Blueprint::macro('dropAuditable', function () {
            $this->dropForeign($this->createIndexName('foreign', ['updated_by_type']));
            $this->dropForeign($this->createIndexName('foreign', ['updated_by_id']));
            $this->dropForeign($this->createIndexName('foreign', ['created_by_type']));
            $this->dropForeign($this->createIndexName('foreign', ['created_by_id']));
            $this->dropColumn(['updated_by_type', 'updated_by_id', 'created_by_type', 'created_by_id']);
        });

        Blueprint::macro('auditableAndTimestamps', function ($precision = 0) {
            $this->timestamp('created_at', $precision)->nullable();
            $this->integer('created_by_id')->unsigned()->nullable();
            $this->string('created_by_type')->nullable();
            $this->timestamp('updated_at', $precision)->nullable();
            $this->integer('updated_by_id')->unsigned()->nullable();
            $this->string('updated_by_type')->nullable();
        });

        Blueprint::macro('dropauditableAndTimestamps', function () {
            $this->dropForeign($this->createIndexName('foreign', ['updated_by_type']));
            $this->dropForeign($this->createIndexName('foreign', ['updated_by_id']));
            $this->dropForeign($this->createIndexName('foreign', ['updated_at']));
            $this->dropForeign($this->createIndexName('foreign', ['created_by_type']));
            $this->dropForeign($this->createIndexName('foreign', ['created_by_id']));
            $this->dropForeign($this->createIndexName('foreign', ['created_at']));
            $this->dropColumn(['updated_by_type', 'updated_by_id', 'updated_at', 'created_by_type', 'created_by_id', 'created_at']);
        });
    }
}
