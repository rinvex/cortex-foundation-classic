# Cortex Foundation Change Log

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](CONTRIBUTING.md).


## [v8.2.2] - 2023-06-30
- Catch empty modules & extensions array use-case

## [v8.2.1] - 2023-06-29
- Update overridden classes to match Laravel v10 logic
- Refactor module/extension resource auto discovery
- Simplify rinvex/composer namespaces

## [v8.2.0] - 2023-06-23
- Remove useless ,DS_Store
- Rename modules manifest service names
- Move tenant features to cortex/tenants module from cortex/foundation
- Override translation FileLoader to support multiple hints per namespace
- Add module extensions support to resource loading and publishing
- Check if IoC service container has `request.tenant` registered first before using
- Decoupling: Move global helpers, route patterns and middleware from cortex/tenants module

## [v8.1.1] - 2023-05-02
- Laravel v10: The deprecated Redirect::home method has been removed. Instead, your application should redirect to an explicitly named route
- Laravel v10: fix update ShowModelCommand console command namespace
- Fix changelog format

## [v8.1.0] - 2023-05-02
- Add support for Laravel v11, and drop support for Laravel v9
- Since Laravel 9.2, this Middleware is included in laravel/framework. You can use the provided middleware, which should be compatible with the Middleware and config provided in this package. See https://github.com/laravel/laravel/pull/5825/files for the changes.
- Upgrade vinkla/hashids to v11.0 from v10.0
- Upgrade spatie/laravel-sitemap to v6.3 from v6.1
- Normalize maatwebsite/excel to v3.1.0 from v3.1.40
- Upgrade mcamara/laravel-localization to v1.8 from v1.7
- Upgrade mariuzzo/laravel-js-localization to v1.10 from v1.0
- Upgrade yajra/laravel-datatables-oracle to v10.4 from v10.0
- Upgrade yajra/laravel-datatables-html to v10.0 from v9.0
- Upgrade yajra/laravel-datatables-buttons to v10.0 from v9.0
- Upgrade proengsoft/laravel-jsvalidation to v4.8 from v4.7
- Upgrade spatie/laravel-activitylog to v4.7 from v4.4
- Update yajra/laravel-datatables-fractal to v10.0 from v9.0
- Update watson/validating to v8.0 from v7.0
- Update phpunit to v10.1 from v9.5
- Fix robot follow
- Disable DFS by default
- add morph map for activitylog model (#323)

## [v8.0.1] - 2023-01-09
- Revert how Diglactic/Breadcrumbs handles route parameters
  https://github.com/diglactic/laravel-breadcrumbs/pull/52

## [v8.0.0] - 2023-01-09
- Drop PHP v8.0 support and update composer dependencies
- Add missing model:show artisan command
- Check first if accessareas IoC service exists before using, this fix issues happens with exception handler when there's no access area detected!
- Update and tweak DFS implementation & exception handling
- Require relation morphMap
- Drop PHP v8.0 support and enforce consistency
- Update composer dependencies
- Utilize PHP 8.1 attributes feature for artisan commands
- Fix app namespace issues by overriding the default behavior
- Add support for environment variable DFS_ENABLED

## [v7.3.23] - 2023-01-05
- Return explicit HTML field instead of using FormBuilder to avoid populating the field with old field value after redirects. We need a freshly generated value everytime.
- Skip DFS on AJAX requests at the moment
- Add support for DFS enable/disable

## [v7.3.22] - 2023-01-02
- fix dfsToken function type hint (#304)

## [v7.3.21] - 2022-12-30
- Fix yajra/laravel-datatables-fractal compatibility #297

## [v7.3.20] - 2022-12-30
- Whitelist datatable columns to avoid invalid columns sent from client-side which might be a security issue in some scenarios
- EloquentPresenceVerifier compatibility: Add support for model path instead of table name
- Add support for unique_with Validator Rule natively in Cortex, no third-party packages required
- Prevent Duplicate Form Submission (DFS)
- Update livewire assets
- use slug instead of name when call firstOrCreate for accessarea since name is not unique and translatable and could have a different value (#298)
- use route_domains helper to get current tenant domains (#295)
- add datatable blades at tenantarea (#287)

## [v7.3.19] - 2022-10-03
- Override session.cookie dynamically for tenants
- Improve HTTP Kernel global middleware enable/disable
- Move middleware registration from HTTP Kernel to module bootstrap
- Move SetAuthDefaults middleware to cortex/auth from cortex/foundation
- Remove useless EncryptCookies middleware override
- Reorder composer dependencies
- Lock psr/simple-cache package to v2.x since maatwebsite/excel is not compatible with v3.x
- refactor: register commands in artisan service #44257 https://github.com/laravel/framework/pull/44257/files
- Fix validator when a table name is provided instead of a model

## [v7.3.18] - 2022-09-02
- Add support for per model enable/disable scoped validation

## [v7.3.17] - 2022-08-30
- Add a new adminarea.header.system menu
- Tweak form timestamps
- Refactor EloquentPresenceVerifier and override the default Validator service
- Update exists and unique validation rules to use models instead of tables
- Revert "Swap the `EloquentPresenceVerifier` model from `Cortex\Foundation\Models\AbstractModel`,"
- Enable trailing slash by default and change middleware order to be executed earlier
- Add a new middleware to trim www. host prefix
- Override TrustProxies middleware to make it configurable, and allow any proxy by default
- Update docblock
- Revert "Add support for app settings cache path"
- Fix exception handler redirect on ModelNotFoundException
- Revert "Apply tenantable global scope on Eloquent Presence Verifier (i.e. used in validation rules like unique & exists)"
- Swap the `EloquentPresenceVerifier` model from `Cortex\Foundation\Models\AbstractModel`, to the actual model. This is used by the validator instance, for validation rules (exists/unique), used by FormRequests, and also useful for "Tenantable" models to enforce the relevant query constraints.
- Sync with parent \Illuminate\Foundation\Http\FormRequest
- Clean the breadcrumbs definition and utilize parent features
- Improve "exists" validation with array values
- Apply tenantable global scope on Eloquent Presence Verifier (i.e. used in validation rules like unique & exists)
- Fix infinite redirects issue with intended URLs

## [v7.3.16] - 2022-08-06
- FIx datatables authorization to check if there's authenticated user

## [v7.3.15] - 2022-08-06
- Fix datatables authorized actions

## [v7.3.14] - 2022-08-06
- Tweak model IDs hashing to support configurable hashed keys

## [v7.3.13] - 2022-07-24
- Fix datatables checkbox select-row options
- Fix audit ability check for import logs
- Refactor datatables bulk actions and check if action is enabled and authorized (closes #275)
- fix buildColumnByCollection method signature (#280)
- use session pull function to delete the item after retrieve it (#281)
- use full function instead of current to get query string parameters (#282)

## [v7.3.12] - 2022-07-02
- Fix Livewire wrong AbstractComponent class name

## [v7.3.11] - 2022-06-22
- Fix datatables ajax method signature
- Fix datatables method signature compatibility
- This override no longer needed, as it's been implemented in the core package
  - Purpose: Override default DataTables DataProcessor to avoid escaping non-string values 
  - Ref: https://github.com/yajra/laravel-datatables/commit/65160b67be5ba59e79a0095824539170c4bbef6e

## [v7.3.10] - 2022-06-20
- Composer require maatwebsite/excel

## [v7.3.9] - 2022-06-20
- Update composer dependencies
  - league/fractal to ^0.20.0 from ^0.19.0
  - yajra/laravel-datatables-html to ^9.0.0 from ^4.41.0
  - yajra/laravel-datatables-fractal to ^9.0.0 from ^1.6.0
  - yajra/laravel-datatables-buttons to ^9.0.0 from ^4.13.0
  - yajra/laravel-datatables-oracle to ^10.0.0 from ^9.19.0
  - fruitcake/laravel-cors to ^3.0.0 from ^2.0.0",
  - symfony/console to ^6.1.0" from ^6.0.0"
  - symfony/finder to ^6.1.0" from ^6.0.0"
  - symfony/http-kernel to ^6.1.0" from ^6.0.0"

## [v7.3.8] - 2022-05-17
- Add support for menu list item attributes
- Add support for dynamic scheduled tasks discovery
- Override Spatie Media model to support Hashids
- Fix media deletion issues and media model implicit binding
- Revert "Fix validation error redirections"

## [v7.3.7] - 2022-05-01
- Fix validation error redirections
- Add support for app settings cache path

## [v7.3.6] - 2022-03-12
- Fix GenericController routes to be per accessarea
  - Reason: we can not use POST requests between accessareas since each accessarea has it's own session, thus CSRF will always fail because it's different
- return empty string if accessarea doesn't have any domains
- Fix datatable import extensions to be dynamic/configurable
- WIP Refactor & Simplify datatables import functionality
- Update composer dependency codedungeon/phpunit-result-printer
- Enforce form actions routePrefix consistency
- Update JS global variables & add routeDomains support
- Add datatables routePrefix support
- Fix datatables export to export all columns if no visible columns requested
- Fix route domain parameter binding
  - 1. If route exists, bound, and has the accessarea parameter: 
  - 2. If route does not exist, but accessed through a registered domain name 
  - 3. If route does not exist, and accessed via non-registered domain or IP address
- Merge branch 'develop' of github.com:rinvex/cortex-foundation into develop
- ImgBot: Optimize images (#266)
- Refactor and simplify datatable import functionality and upgrade maatwebsite/excel to v3

## [v7.3.5] - 2022-02-20
- Override Datatables DataArrayTransformer to export columns with their db names instead of their localized titles
- Fix dropzone accepted file extensions and make it configurable

## [v7.3.4] - 2022-02-17
- Bind missing db:seed artisan command

## [v7.3.3] - 2022-02-16
- Filter module paths if doesn't exist

## [v7.3.2] - 2022-02-14
- Add todo note for future improvement of artisan autoloading
- L9: Fix compatibility issue with Laroute
- L9: Update Artisan & Migration service providers
- Add cache migrations

## [v7.3.1] - 2022-02-14
- L9: drop fideloper/proxy dependency
- L9: update Application::handle signature compatobol;compatibility

## [v7.3.0] - 2022-02-14
- Apply Laravel v9 updates
- Apply PHP v8 nullsafe operator
- Update composer dependencies to Laravel v9

## [v7.2.9] - 2022-02-13
- Require Livewire composer dependency

## [v7.2.8] - 2022-02-13
- Clean migrations
- ExceptionHandler > ModelNotFound: Fix issue when route pattern doesn't exist
- Update reuqest()->guard() logic to support dynamic accessarea URL prefixes
- Request guard: add support for url prefix & accessarea-specific domain check
- Move Relation::morphMap to module bootstrap
- Add Livewire component example
- Sanitize request route parameter on ModelNotFoundException
- Update routes to use class based definitions
- Enfoce consistency
- Add artisan command shortcut
- Add Livewire support
- Add getResourcePath & getModulePath methods to artisan commands
- Separate AuthorizesRequests into it's own trait
- Update broadcasting channels discovery
- Add support for model HasFactory

## [v7.2.7] - 2022-01-02
- Add support for centralarea & absentarea
- Throw an exception in case of potentially infinite redirects
- Fix saveStateUntilAuthentication logic to handle POST requests correctly
- Fix saveStateUntilAuthentication logic to persist data between requests
- Rename afterAuthentication to saveStateUntilAuthentication

## [v7.2.6] - 2021-12-20
- Fix route missing accessarea parameter issue

## [v7.2.5] - 2021-11-18
- Check if existing APP_KEY is a valid key before creating a new one

## [v7.2.4] - 2021-11-18
- Override KeyGenerateCommand to add ifnot option that checks if key exists before making any changes

## [v7.2.3] - 2021-10-25
- Update TrustHosts middleware to support multiple domains and fix untrusted host issues on production
- Simplify code formate and remove unnecessary quotes and braces!

## [v7.2.2] - 2021-10-22
- Update .styleci.yml fixers
- Rearrange middleware priorities
- Cache guard locally for future usage
- Refactor request()->guard() and request()->accessarea()
- Refactor route domain variables to be accessarea specific
- Swap first and second use cases to retrieve guard earlier if possible

## [v7.2.1] - 2021-10-11
- Rename route parameter 'central_domain' to 'routeDomain'
- Rename central_domain to routeDomain and remove tenant_domain route parameters
- Rename route pattern central_domain to routeDomain and move to cortex/tenants module
- Fix session input old data issues
- Check before detaching accessareas if deleted entity was soft deleted

## [v7.2.0] - 2021-08-22
- Drop PHP v7 support, and upgrade rinvex package dependencies to next major version

## [v7.1.0] - 2021-08-21
- Major changes breaking changes (session isolation per guard), supposed to come in v7.0.0 but got delayed
- Override core RoutingServiceProvider instead of extending Redirector & UrlGenerator instances
- Override SessionServiceProvider to use custom SessionGuard (requires config/app.php change)
- Override SessionManager to use custom Session Store & Encrypted Store that supports session isolation per guard
- Add EncryptCookies middleware on top of priority list
- Fix exception handler issues with accessarea when it's not available (very early exception)

## [v7.0.0] - 2021-08-18
- Breaking Changes and updates
- Update composer dependency cortex/foundation to v7
- Refactor webpack module config
- Drop using nohttpcache middleware for tenantarea routes
- Remove no longer used domain() global helper - This is now the responsibility of rinvex/laravel-tenants
- Handle AbstractTenantException
- Use relative css paths for node modules
- Remove useless css and image assets
- Bind and unbind central & tenant domain route params
- Fix minor typo
- Fix wrong accessarea
- Disable is_scope for adminarea & managerarea
- Register central and tenant domain route patterns
- Register routes to either central or tenant domains
- Move route binding, patterns, and middleware to module bootstrap

## [v6.0.43] - 2021-08-08
- Remove useless methods for now

## [v6.0.42] - 2021-08-08
- fix accessareas migration (#238)

## [v6.0.41] - 2021-08-07
- Check database connection before accessing accessareas

## [v6.0.40] - 2021-08-07
- Upgrade spatie/laravel-activitylog to v4

## [v6.0.39] - 2021-08-06
- Refactor accessareas and make it dynamically controlled through adminarea
- Move route prefixes to accessareas db table responsibility
- Simplify route prefixes
- Add route_prefix module helper
- Move application layer helpers to from rinvex/laravel-support
- Refactor accessarea obscure and indexable config options
- Refactor module resources loading, base on active modules and accessareas
- Register accessareas into service container, early before booting any module service providers!
- Fix canonical URLs and meta tag SEO issues
- Rename areas to accessareas
- Update composer dependencies
- Fix redirect->saveStateUntilAuthentication() execution condition (#231)

## [v6.0.38] - 2021-05-28
- Rename ExceptionHandler and stop reporting generic exceptions

## [v6.0.37] - 2021-05-27
- Rollback AccountException to GenericException and move to cortex/foundation

## [v6.0.36] - 2021-05-25
- Fix compatibility issue with v7 of diglactic/laravel-breadcrumbs

## [v6.0.35] - 2021-05-25
- Update composer dependencies diglactic/laravel-breadcrumbs to v7

## [v6.0.34] - 2021-05-25
- Fix wrong overriden trait
- Replace deprecated `Breadcrumbs::register` with `Breadcrumbs::for`

## [v6.0.33] - 2021-05-24
- Merge rules instead of resetting, to allow adequate model override
- Drop common blade views in favor for accessarea specific views
- Check if request is an API, which expectsJson
- Refactor returned HTTP status codes on the Exception Handler
- Catch LanguageLoaderException
- Report AccountException like others, no reason to ignore
- Refactor GenericException to AccountException and move to cortex/auth and return more accurate HTTP status code

## [v6.0.32] - 2021-05-11
- Fix constructor initialization order (fill attributes should come next after merging fillables & rules)

## [v6.0.31] - 2021-05-11
- Uninstall spatie/laravel-settings composer packaage

## [v6.0.30] - 2021-05-08
- Install spatie/laravel-settings composer packaage
- Set validation rules in constructor for consistency & flexibility
- Remove duplicate button options, it's already merged from default config

## [v6.0.29] - 2021-05-07
- Upgrade to GitHub-native Dependabot (#221)
- Rename migrations to always run after rinvex core packages

## [v6.0.28] - 2021-05-04
- Fix container service check issue 
  - Use container check instead of make app()->has('request.tenant')	c73daa0	Abdelrahman Omran <me@omranic.com>	May 2, 2021 at 2:30 PM
- Fix app('request.tenant') check
- Save state, and redirect, or resubmit form after authentication
- Override Diglactic\Breadcrumbs\Manager
- Improve readability and simplify the code using PHP v7.4 arrow functions
- Prioritize loading all module resources, not only service providers

## [v6.0.27] - 2021-04-27
- Rollback to original felixkiss/uniquewith-validator package
- ability to override one option only without need to override all options (#217)

## [v6.0.26] - 2021-04-07
- fix updatedBy timestamps relation name (#216)

## [v6.0.25] - 2021-03-15
- Fix accessarea for tenantarea

## [v6.0.24] - 2021-03-15
- check if route exists before using it (#215)

## [v6.0.23] - 2021-03-02
- Override `ComponentMakeCommand`, `StubPublishCommand`, and `TestMakeCommand` artisan commands
- Autoload artisan commands
- Utilize `DeferrableProvider` interface for deferrable service providers
- Add missing job_batches migration

## [v6.0.22] - 2021-03-02
- Delete useless `BelongsToMorph` relation class
- Reverse commit "Tweak middleware abilities authorized resource model name"

## [v6.0.21] - 2021-03-02
- Tweak middleware abilities authorized resource model name
- Rename `auth.defaults.apiguard` and set default value for config option
- Fix setting `auth.defaults.provider` config option
- Enhance `DiscoveryServiceProvider` performance
- Guess guard from: request segments (very early before routes are registered!)
- Move `SetTurbolinksLocationHeaders` from global middleware to `web` route middleware group
- Drop `ThrottleRequests` middleware override

## [v6.0.20] - 2021-03-01
- Override `Request` class for console
- Add support for consolearea to accessarea

## [v6.0.19] - 2021-02-28
- Fix wrong createdBy attribute in form timestamps
- Refactor Request::guard() and Request::accessarea() methods - Support API requests - Fix multiple issues
- Add `SetAuthDefaults` middleware to set Auth defaults globally
- Override `AuthenticationException` handler
- Add `apiarea` to route prefixes and obscured areas
- Improve `bootAuditable` trait method performance
- Drop `Authenticate` middleware override
- Add `UnauthenticatedController` base controller for guest controller actions
- Override `FormRequest` container binding
- Change `DiscoverNavigationRoutes` middleware priority order to change execution to the end of the stack, so session, request, and everything else are already loaded, bound, and ready to go!
- Simplify and utilize request()->user() and request()->guard()
- Simplify and utilize request()->accessarea() - Remove useless `RouteMatched` event listener, and use `request` methods directly instead
- Remove useless middleware parameters
- Use overridden `FormRequest` instead of native class
- Simplify and utilize request()->accessarea()
- Override `FormRequest` class
- Change `Request` class namespace
- Add `guard`, `emailVerifierBroker`, and `passwordResetBroker` methods to the Request class
- Cache accessacrea value for current request
- Remove useless method override
- Override Authorize middleware
- Tweak exception handler to always pass errors instead of warnings
- Add support for accessarea and api detection to core request class
- Move collection `similar` macro to rinvex/laravel-support package responsibility

## [v6.0.18] - 2021-02-15
- Fix wrong artisan GenerateIdeHelperCommand name

## [v6.0.17] - 2021-02-15
- Update core artisan commands - autoload - unload - activate - deactivate - generate ide helper
- Use `request->input()` instead of `request->get()`

## [v6.0.16] - 2021-02-11
- Simplify `SetupRequestOnMatchedRoute` listener and access area retrieval
- Utilize `get_access_area` global helper to get access area - This also fixes some issues with unmatched & unnamed routes
- Refactor form timestamps and auditable field features - created_by - updated_by
- fix bulk action function call (#209)

## [v6.0.15] - 2021-02-07
- Replace silber/bouncer package with custom modified tmp version

## [v6.0.14] - 2021-02-06
- Simplify service provider model registration into IoC
- handle CountryLoaderException and UniversityLoaderException (#206)
- Refactor stubs for resource generation and make commands
- Add missing ObserverMake command
- Tweak authorization layer to support configurable model name (resource) to fix model override issues, and always bind to runtime model, instead of hardcoding
- Add missing phpdoc
- Add missing ObserverMake command
- Move ide-helper generation to artisan command to conditionally run on dev environments only (when dev-package is installed)
- Pass 'module' command option when calling sub-commands
- Fix make command namespaces & paths for different entities
- Conditionally register Debug bar service provider on dev environments
- Skip publishing module resources unless explicitly specified, for simplicity

## [v6.0.13] - 2021-01-16
- Fix module migration autoloader issue

## [v6.0.12] - 2021-01-15
- Add model replication feature
- Upgrade medialibrary db to v9

## [v6.0.11] - 2021-01-02
- Fix XSS security issue with redirect messages

## [v6.0.10] - 2021-01-02
- Move cortex:autoload & cortex:activate commands to cortex/foundation module responsibility

## [v6.0.9] - 2021-01-02
- Move cortex:autoload & cortex:activate commands to cortex/foundation module responsibility

## [v6.0.8] - 2021-01-02
- Refactor Package and Module manifest classes and commands

## [v6.0.7] - 2021-01-01
- Move cortex:autoload & cortex:activate commands to cortex/foundation module responsibility
    - This is because :autoload & :activate commands are registered only if the module already autoloaded, so there is no way we can execute commands of unloaded modules
    - cortex/foundation module is always autoloaded, so it's the logical and reasonable place to register these :autoload & :activate module commands and control other modules from outside

## [v6.0.6] - 2021-01-01
- Run self diagnosis before installation
- Generate application key, and link storage directory before installation
- Publish assets only if explicitly required, otherwise skip for clean installation
- Override artisan StorageLinkCommand to change alert level from error to warn
- Add 'force' option to core autoload & activate artisan command calls

## [v6.0.5] - 2021-01-01
- Run self diagnosis before installation
- Generate application key, and link storage directory before installation
- Publish assets only if explicitly required, otherwise skip for clean installation
- Override artisan StorageLinkCommand to change alert level from error to warn
- Add 'force' option to core autoload & activate artisan command calls

## [v6.0.5] - 2020-12-31
- Skip modules that are not installed via composer
- Rename seeders directory
- Fix debugbar config file path
- Update Clockwork middleware
- Rename core_modules to always_active
- Refactor service provider loading priorities
- Add core activate, deactivate, autoload, unload artisan commands
- Utilize rinvex.composer.always_active config option
- Fix Google font API links
- Override DataTable Builder generateScripts to drop using sprintf - sprintf used to cause issues when using % symbols in attributes

## [v6.0.4] - 2020-12-27
- Tweak and optimize module and custom composer installer

## [v6.0.3] - 2020-12-25
- Fix wrong composer dependency version constraints

## [v6.0.2] - 2020-12-25
- Switch outdated composer dependencies to temporary compatible forks until it's updated

## [v6.0.1] - 2020-12-25
- Add support for PHP v8

## [v6.0.0] - 2020-12-22
- Upgrade to Laravel v8

## [v5.1.14] - 2020-12-16
- Fix merge config in auto discovery

## [v5.1.13] - 2020-12-14
- Fix module config files auto discovery

## [v5.1.12] - 2020-12-12
- Fix OpcacheServiceProvider config file include, and drop route registration

## [v5.1.11] - 2020-12-11
- Add stateSave, responsive, scrollX options to datatables and fix some datatable options
- Import AdminLTE base styles in frontarea theme to inherit needed styles
- Cast $user->getAuthIdentifier() to string to avoid sha1 datatype issues
- Override datatables HTML builder, template method and add pusher support
- Override DebugbarServiceProvider & OpcacheServiceProvider
- Rename routes, channels, menus, breadcrumbs, datatable & form IDs to follow same modular naming conventions
- Tweak and simplify datatables method call and realtime
- Refactor and tweak Eloquent Events
- Ad support for query scopes
- Move EloquentDataTable class to correct overrides namespace
- Refactor and simplify datatables bulk actions
- Add support for datatables bulkRevoke
- Enforce consistent datatables request object usage
- Move datatables options and buttons to config files and support class based override as well
- Refactor frontarea datatables views and add missing partial
- Automatically add query scopes to datatables if exists
- Enforce consistent database table index definition
- Add support for bulkRevoke
- Drop useless datatables ajax method override
- Reset default custom order column: 'name' in ajax method

## [v5.1.10] - 2020-10-05
- Setup Bouncer users model on route matched event
- Whitelist enabled modules instead of blacklisting disabled modules

## [v5.1.9] - 2020-09-22
- Don't report GenericException

## [v5.1.8] - 2020-09-22
- Check if route exists first before redirection on exception handler

## [v5.1.7] - 2020-09-19
- Add create_popup support for datatable buttons

## [v5.1.6] - 2020-09-08
- Check for app()->bound('request.accessarea') && app()->bound('request.guard') first before using in controller constructors

## [v5.1.5] - 2020-08-29
- Fix EventCacheCommand, CastMake, ComponentMake, StubPublish artisan commands

## [v5.1.4] - 2020-08-28
- Refactor request params setup

## [v5.1.3] - 2020-08-27
- Don't crawl page if page is not found a.k.a app('request.accessarea') is not bound

## [v5.1.2] - 2020-08-26
- Tweak auto discovery disabled modules

## [v5.1.1] - 2020-08-25
- Add functionality to activate, deactivate, autoload, unload modules
- Activate module after installation
- Fix service container usage

## [v5.1.0] - 2020-07-16
- Utilize timezones
- Override artisan EventListCommand
- Tweak events discovery in DiscoveryServiceProvider
- fix wrong access for app helper (#147)
- Bind app('request.user') into service container
- Use app('request.tenant') instead of $currentTenant
- Add missing language phrases
- Allow override of meta author, generator, and site name attributes
- Update validation rules
- Enforce consistency
- Copy tinymce plugins

## [v5.0.2] - 2020-06-21
- Tweak subdomain route parameter binding

## [v5.0.1] - 2020-06-19
- Fix request.guard binding issue when running in console

## [v5.0.0] - 2020-06-19
- Update composer dependencies
- Introducing module early bootstrapping feature
- Override core Application foundation class and PackageManifest bootstrap cache builder
- Override LarouteCollection to fix issues with cached routes
- Refactor route parameters to container service binding
- Rename ForgetLocaleRouteParameter to UnbindRouteParameters and change execution order - Plus unbind {subdomain} route parameter
- Refactor route parameters to container service binding
- Rename SetAccessArea Middleware to DiscoverNavigationRoutes
- Merge event discovery into DiscoveryServiceProvider
- Stick to composer version constraints recommendations and ease minimum required version of modules

## [v4.2.0] - 2020-06-15
- Autoload config, views, language, menus, breadcrumbs, and migrations for all modules
- Add intl-tel-input images to webpack processing
- Drop using rinvex/laravel-cacheable from core packages for more flexibility
    - Caching should be handled on the application layer, not enforced from the core packages
- Drop PHP 7.2 & 7.3 support from travis
- Tweak selected_ids collection filtration and check

## [v4.1.1] - 2020-05-30
- Update composer dependencies

## [v4.1.0] - 2020-05-30
- With the significance of recent updates, new minor release required

## [v4.0.9] - 2020-05-30
- Disable datatables button fade effect
- Update datatables query method
- Remove useless datatables checkbox config option
- Add datatables checkbox column for bulk actions
- Always use parent::query() when overriding datatables query() methods
- Drop using strip_tags on redirect identifiers as they will use ->getRouteKey() which is already safe
- Auto close bulk menu after child actions are clicked
- Move datatable buttons creation to separate method
- Add missing phrases for datatables bulk actions
- Add support for datatables to render bulk delete, activate, and deactivate actions implictly
- Reorder datatables buttons
- Add support for customizing pageLength and lengthMenu datatables options
- Convert datatables to work completely in POST ajax requests instead of GET requests for better security and to overcome long query strings / requests parameters and add support for datatable filter forms
- Add missing phrases for datatables bulk actions
- Add support for customizing pageLength and lengthMenu datatables options
- Rename selectedIds variables for consistency
- Refactor model CRUD dispatched events
- Refactor datatables default options and serverside buttons and support selected rows
- Move broadcasting authentication route to cortex/auth module
- Fire custom model events from CRUD actions
- Explicitly specify relationship attributes
- Load module routes automatically
- Revert back breadcrumbs escaping, this is handled individually as we may pass HTML intentionally
- Strip tags of language phrase parameters with potential user inputs
- Escape breadcrumb titles
- Escape language phrases
- Update model validation rules
- Add strip_tags validation rule to string fields
- Remove default indent size config

## [v4.0.8] - 2020-04-12
- Fix ServiceProvider registerCommands method compatibility

## [v4.0.7] - 2020-04-09
- Tweak artisan command registration
- Refactor publish command and allow multiple resource values

## [v4.0.6] - 2020-04-07
- Fix wrong module webpack config file name

## [v4.0.5] - 2020-04-04
- Enforce consistent artisan command tag namespacing
- Enforce consistent package namespace
- Drop laravel/helpers usage as it's no longer used
- Upgrade silber/bouncer composer package

## [v4.0.4] - 2020-03-20
- Add shortcut -f (force) for artisan publish commands
- Fix migrations path condition
- Convert database int fields into bigInteger
- Upgrade spatie/laravel-medialibrary to v8.x
- Fix couple issues and enforce consistency

## [v4.0.3] - 2020-03-16
- Update compatibility with Laravel v7.x

## [v4.0.2] - 2020-03-15
- Fix incompatible package version league/fractal

## [v4.0.1] - 2020-03-15
- Fix wrong package version laravelcollective/html

## [v4.0.0] - 2020-03-15
- Upgrade to Laravel v7.1.x & PHP v7.4.x

## [v3.1.4] - 2020-03-13
- Install felixkiss/uniquewith-validator composer package

## [v3.1.3] - 2020-03-13
- Tweak TravisCI config
- Fix production artisan commands registration	764d9ee	Abdelrahman Omran <me@omranic.com>	Jan 16, 2020 at 9:39 AM
- Add migrations autoload option to the package
- Tweak service provider `publishesResources` & `autoloadMigrations`
- Update composer dependencies
- Update tenant name in tenantarea
- Update meta tags for tenant in tenantarea
- Update StyleCI config
- Drop using global helpers
- Check if ability exists before seeding

## [v3.1.2] - 2019-12-18
- Fix `migrate:reset` args as it doesn't accept --step
- Fix Crawling Robots indexable access areas check (#97)
    - fix should index checker by check if access area listed in cortex.foundation.route.prefix
    - Fix Crawling Robots indexable access areas check
- Auto discover events listeners and register events automatically

## [v3.1.1] - 2019-12-04
- Add ajax filters capabilities to datatables
- Tweak obscure feature
- Add id attribute to the main div content in body
- Fix file size validation rule

## [v3.1.0] - 2019-11-23
- Fix Crawling Robots indexable access areas feature
- Rename AccessArea Middleware to SetAccessArea
- Arrange SetAccessArea & Reauthenticate middleware priorities order
- Update error views
- Override ThrottleRequests middleware
- Handle ThrottleRequestsException
- Remember current URL for later redirect, when unauthenticated exception handled

## [v3.0.3] - 2019-10-14
- Update menus & breadcrumbs event listener to accessarea.ready
- Fix wrong dependencies letter case
- Fix & tweak event dispatcher for menus & breadcrumbs to use middleware instead of AbstractController
- Update middleware priority
- Remove unused overridden middleware to reduce confusion
- Fix guard names singular / plural

## [v3.0.2] - 2019-10-06
- Refactor menus and breadcrumb bindings to utilize event dispatcher
- Fix compatibility with Laravel v6
- Utilize cortex.foundation.route.trailing_slash config option correctly
- Refactor MigrationServiceProvider
- Enforce consistency
- Fix and optimize Laravel JS Localization binding

## [v3.0.1] - 2019-09-24
- Add missing laravel/helpers composer package

## [v3.0.0] - 2019-09-23
- Upgrade to Laravel v6 and update dependencies

## [v2.2.5] - 2019-09-03
- Fix size validation rule

## [v2.2.4] - 2019-09-03
- Fix issue with: Update HttpKernel to use Authenticate middleware under App namespace

## [v2.2.3] - 2019-09-03
- Conditionally inject Clockwork middleware to web group if not on production environment

## [v2.2.2] - 2019-09-03
- Enforce profile_picture and cover_photo image validation rules & update media config
- Update media config options
- use set function instead of header as BinaryFileResponse doesn't have header funcation (#90)
- Use $_SERVER instead of $_ENV for PHPUnit
- Move TrustProxies to highest priority - fixes maintenance mode ip whitelist if behind proxy e.g. Cloudflare (https://github.com/laravel/laravel/pull/5055)
- Update HttpKernel to use Authenticate middleware under App namespace

## [v2.2.1] - 2019-08-03
- Tweak menus & breadcrumbs performance

## [v2.2.0] - 2019-08-03
- Upgrade composer dependencies
- Rename datatable views
- Disable default AuthenticateSession middleware
- Use singular guard name instead of plural

## [v2.1.3] - 2019-06-03
- Enforce latest composer package versions

## [v2.1.2] - 2019-06-03
- Update publish commands to support both packages and modules natively

## [v2.1.1] - 2019-06-02
- Fix yajra/laravel-datatables-fractal and league/fractal compatibility

## [v2.1.0] - 2019-06-02
- Update composer deps
- Drop PHP 7.1 travis test
- Override Laroute & JSLocalization artisan commands
- Update edvinaskrucas/notification to dev-master to fix Laravel 5.8 compatibility issues (not tagged yet)
- Refactor migrations and artisan commands, and tweak service provider publishes functionality

## [v2.0.3] - 2019-03-04
- Fix exception handler method signature compatibility issue

## [v2.0.2] - 2019-03-04
- Revert "Move lord/laroute composer dependency to project level"

## [v2.0.1] - 2019-03-04
- Move lord/laroute composer dependency to project level

## [v2.0.0] - 2019-03-03
- Require PHP 7.2 & Laravel 5.8
- Activate AuthenticateSession middleware
- Utilize support helpers
- Fix json/array casting type
- Refactor abilities seeding
- Refactor managed roles/abilities retrieval
- Drop duplicate useless overridden method

## [v1.0.4] - 2019-01-03
- Fix MySQL / PostgreSQL json column compatibility
- Update spatie/laravel-activitylog functionality
- Rename environment variable QUEUE_DRIVER to QUEUE_CONNECTION

## [v1.0.3] - 2018-12-22
- Update composer dependencies
- Add PHP 7.3 support to travis
- Add signed and verified middleware
- Simplify and flatten resources/public directories
- Simplify $route->getAction() usage
- Simplify controller actions
    - Move area roles & abilities retrieval to global helper
- Fix favicon paths
- Remove useless AuthenticateWithBasicAuth override (Laravel v5.7)

## [v1.0.2] - 2018-10-25
- Correct commit "Stop ignoring file on export, we need it in new module generation"

## [v1.0.1] - 2018-10-25
- Stop ignoring file on export, we need it in new module generation

## [v1.0.0] - 2018-10-01
- Support Laravel v5.7, bump versions and enforce consistency

## [v0.0.2] - 2018-09-22
- Too much changes to list here!!

## v0.0.1 - 2017-03-14
- Tag first release

[v8.2.2]: https://github.com/rinvex/cortex-foundation/compare/v8.2.1...v8.2.2
[v8.2.1]: https://github.com/rinvex/cortex-foundation/compare/v8.2.0...v8.2.1
[v8.2.0]: https://github.com/rinvex/cortex-foundation/compare/v8.1.1...v8.2.0
[v8.1.1]: https://github.com/rinvex/cortex-foundation/compare/v8.1.0...v8.1.1
[v8.1.0]: https://github.com/rinvex/cortex-foundation/compare/v8.0.1...v8.1.0
[v8.0.1]: https://github.com/rinvex/cortex-foundation/compare/v8.0.0...v8.0.1
[v8.0.0]: https://github.com/rinvex/cortex-foundation/compare/v7.3.23...v8.0.0
[v7.3.23]: https://github.com/rinvex/cortex-foundation/compare/v7.3.22...v7.3.23
[v7.3.22]: https://github.com/rinvex/cortex-foundation/compare/v7.3.21...v7.3.22
[v7.3.21]: https://github.com/rinvex/cortex-foundation/compare/v7.3.20...v7.3.21
[v7.3.20]: https://github.com/rinvex/cortex-foundation/compare/v7.3.19...v7.3.20
[v7.3.19]: https://github.com/rinvex/cortex-foundation/compare/v7.3.18...v7.3.19
[v7.3.18]: https://github.com/rinvex/cortex-foundation/compare/v7.3.17...v7.3.18
[v7.3.17]: https://github.com/rinvex/cortex-foundation/compare/v7.3.16...v7.3.17
[v7.3.16]: https://github.com/rinvex/cortex-foundation/compare/v7.3.15...v7.3.16
[v7.3.15]: https://github.com/rinvex/cortex-foundation/compare/v7.3.14...v7.3.15
[v7.3.14]: https://github.com/rinvex/cortex-foundation/compare/v7.3.13...v7.3.14
[v7.3.13]: https://github.com/rinvex/cortex-foundation/compare/v7.3.12...v7.3.13
[v7.3.12]: https://github.com/rinvex/cortex-foundation/compare/v7.3.11...v7.3.12
[v7.3.11]: https://github.com/rinvex/cortex-foundation/compare/v7.3.10...v7.3.11
[v7.3.10]: https://github.com/rinvex/cortex-foundation/compare/v7.3.9...v7.3.10
[v7.3.9]: https://github.com/rinvex/cortex-foundation/compare/v7.3.8...v7.3.9
[v7.3.8]: https://github.com/rinvex/cortex-foundation/compare/v7.3.7...v7.3.8
[v7.3.7]: https://github.com/rinvex/cortex-foundation/compare/v7.3.6...v7.3.7
[v7.3.6]: https://github.com/rinvex/cortex-foundation/compare/v7.3.5...v7.3.6
[v7.3.5]: https://github.com/rinvex/cortex-foundation/compare/v7.3.4...v7.3.5
[v7.3.4]: https://github.com/rinvex/cortex-foundation/compare/v7.3.3...v7.3.4
[v7.3.3]: https://github.com/rinvex/cortex-foundation/compare/v7.3.2...v7.3.3
[v7.3.2]: https://github.com/rinvex/cortex-foundation/compare/v7.3.1...v7.3.2
[v7.3.1]: https://github.com/rinvex/cortex-foundation/compare/v7.3.0...v7.3.1
[v7.3.0]: https://github.com/rinvex/cortex-foundation/compare/v7.2.9...v7.3.0
[v7.2.9]: https://github.com/rinvex/cortex-foundation/compare/v7.2.8...v7.2.9
[v7.2.8]: https://github.com/rinvex/cortex-foundation/compare/v7.2.7...v7.2.8
[v7.2.7]: https://github.com/rinvex/cortex-foundation/compare/v7.2.6...v7.2.7
[v7.2.6]: https://github.com/rinvex/cortex-foundation/compare/v7.2.5...v7.2.6
[v7.2.5]: https://github.com/rinvex/cortex-foundation/compare/v7.2.4...v7.2.5
[v7.2.4]: https://github.com/rinvex/cortex-foundation/compare/v7.2.3...v7.2.4
[v7.2.3]: https://github.com/rinvex/cortex-foundation/compare/v7.2.2...v7.2.3
[v7.2.2]: https://github.com/rinvex/cortex-foundation/compare/v7.2.1...v7.2.2
[v7.2.1]: https://github.com/rinvex/cortex-foundation/compare/v7.2.0...v7.2.1
[v7.2.0]: https://github.com/rinvex/cortex-foundation/compare/v7.1.0...v7.2.0
[v7.1.0]: https://github.com/rinvex/cortex-foundation/compare/v7.0.0...v7.1.0
[v7.0.0]: https://github.com/rinvex/cortex-foundation/compare/v6.0.43...v7.0.0
[v6.0.43]: https://github.com/rinvex/cortex-foundation/compare/v6.0.42...v6.0.43
[v6.0.42]: https://github.com/rinvex/cortex-foundation/compare/v6.0.41...v6.0.42
[v6.0.41]: https://github.com/rinvex/cortex-foundation/compare/v6.0.40...v6.0.41
[v6.0.40]: https://github.com/rinvex/cortex-foundation/compare/v6.0.39...v6.0.40
[v6.0.39]: https://github.com/rinvex/cortex-foundation/compare/v6.0.38...v6.0.39
[v6.0.38]: https://github.com/rinvex/cortex-foundation/compare/v6.0.37...v6.0.38
[v6.0.37]: https://github.com/rinvex/cortex-foundation/compare/v6.0.36...v6.0.37
[v6.0.36]: https://github.com/rinvex/cortex-foundation/compare/v6.0.35...v6.0.36
[v6.0.35]: https://github.com/rinvex/cortex-foundation/compare/v6.0.34...v6.0.35
[v6.0.34]: https://github.com/rinvex/cortex-foundation/compare/v6.0.33...v6.0.34
[v6.0.33]: https://github.com/rinvex/cortex-foundation/compare/v6.0.32...v6.0.33
[v6.0.32]: https://github.com/rinvex/cortex-foundation/compare/v6.0.31...v6.0.32
[v6.0.31]: https://github.com/rinvex/cortex-foundation/compare/v6.0.30...v6.0.31
[v6.0.30]: https://github.com/rinvex/cortex-foundation/compare/v6.0.29...v6.0.30
[v6.0.29]: https://github.com/rinvex/cortex-foundation/compare/v6.0.28...v6.0.29
[v6.0.28]: https://github.com/rinvex/cortex-foundation/compare/v6.0.27...v6.0.28
[v6.0.27]: https://github.com/rinvex/cortex-foundation/compare/v6.0.26...v6.0.27
[v6.0.26]: https://github.com/rinvex/cortex-foundation/compare/v6.0.25...v6.0.26
[v6.0.25]: https://github.com/rinvex/cortex-foundation/compare/v6.0.24...v6.0.25
[v6.0.24]: https://github.com/rinvex/cortex-foundation/compare/v6.0.23...v6.0.24
[v6.0.23]: https://github.com/rinvex/cortex-foundation/compare/v6.0.22...v6.0.23
[v6.0.22]: https://github.com/rinvex/cortex-foundation/compare/v6.0.21...v6.0.22
[v6.0.21]: https://github.com/rinvex/cortex-foundation/compare/v6.0.20...v6.0.21
[v6.0.20]: https://github.com/rinvex/cortex-foundation/compare/v6.0.19...v6.0.20
[v6.0.19]: https://github.com/rinvex/cortex-foundation/compare/v6.0.18...v6.0.19
[v6.0.18]: https://github.com/rinvex/cortex-foundation/compare/v6.0.17...v6.0.18
[v6.0.17]: https://github.com/rinvex/cortex-foundation/compare/v6.0.16...v6.0.17
[v6.0.16]: https://github.com/rinvex/cortex-foundation/compare/v6.0.15...v6.0.16
[v6.0.15]: https://github.com/rinvex/cortex-foundation/compare/v6.0.14...v6.0.15
[v6.0.14]: https://github.com/rinvex/cortex-foundation/compare/v6.0.13...v6.0.14
[v6.0.13]: https://github.com/rinvex/cortex-foundation/compare/v6.0.12...v6.0.13
[v6.0.12]: https://github.com/rinvex/cortex-foundation/compare/v6.0.11...v6.0.12
[v6.0.11]: https://github.com/rinvex/cortex-foundation/compare/v6.0.10...v6.0.11
[v6.0.10]: https://github.com/rinvex/cortex-foundation/compare/v6.0.9...v6.0.10
[v6.0.9]: https://github.com/rinvex/cortex-foundation/compare/v6.0.8...v6.0.9
[v6.0.8]: https://github.com/rinvex/cortex-foundation/compare/v6.0.7...v6.0.8
[v6.0.7]: https://github.com/rinvex/cortex-foundation/compare/v6.0.6...v6.0.7
[v6.0.6]: https://github.com/rinvex/cortex-foundation/compare/v6.0.5...v6.0.6
[v6.0.5]: https://github.com/rinvex/cortex-foundation/compare/v6.0.4...v6.0.5
[v6.0.4]: https://github.com/rinvex/cortex-foundation/compare/v6.0.3...v6.0.4
[v6.0.3]: https://github.com/rinvex/cortex-foundation/compare/v6.0.2...v6.0.3
[v6.0.2]: https://github.com/rinvex/cortex-foundation/compare/v6.0.1...v6.0.2
[v6.0.1]: https://github.com/rinvex/cortex-foundation/compare/v6.0.0...v6.0.1
[v6.0.0]: https://github.com/rinvex/cortex-foundation/compare/v5.1.14...v6.0.0
[v5.1.14]: https://github.com/rinvex/cortex-foundation/compare/v5.1.13...v5.1.14
[v5.1.13]: https://github.com/rinvex/cortex-foundation/compare/v5.1.12...v5.1.13
[v5.1.12]: https://github.com/rinvex/cortex-foundation/compare/v5.1.11...v5.1.12
[v5.1.11]: https://github.com/rinvex/cortex-foundation/compare/v5.1.10...v5.1.11
[v5.1.10]: https://github.com/rinvex/cortex-foundation/compare/v5.1.9...v5.1.10
[v5.1.9]: https://github.com/rinvex/cortex-foundation/compare/v5.1.8...v5.1.9
[v5.1.8]: https://github.com/rinvex/cortex-foundation/compare/v5.1.7...v5.1.8
[v5.1.7]: https://github.com/rinvex/cortex-foundation/compare/v5.1.6...v5.1.7
[v5.1.6]: https://github.com/rinvex/cortex-foundation/compare/v5.1.5...v5.1.6
[v5.1.5]: https://github.com/rinvex/cortex-foundation/compare/v5.1.4...v5.1.5
[v5.1.4]: https://github.com/rinvex/cortex-foundation/compare/v5.1.3...v5.1.4
[v5.1.3]: https://github.com/rinvex/cortex-foundation/compare/v5.1.2...v5.1.3
[v5.1.2]: https://github.com/rinvex/cortex-foundation/compare/v5.1.1...v5.1.2
[v5.1.1]: https://github.com/rinvex/cortex-foundation/compare/v5.1.0...v5.1.1
[v5.1.0]: https://github.com/rinvex/cortex-foundation/compare/v5.0.2...v5.1.0
[v5.0.2]: https://github.com/rinvex/cortex-foundation/compare/v5.0.1...v5.0.2
[v5.0.1]: https://github.com/rinvex/cortex-foundation/compare/v5.0.0...v5.0.1
[v5.0.0]: https://github.com/rinvex/cortex-foundation/compare/v4.2.0...v5.0.0
[v4.2.0]: https://github.com/rinvex/cortex-foundation/compare/v4.1.1...v4.2.0
[v4.1.1]: https://github.com/rinvex/cortex-foundation/compare/v4.1.0...v4.1.1
[v4.1.0]: https://github.com/rinvex/cortex-foundation/compare/v4.0.8...v4.1.0
[v4.0.9]: https://github.com/rinvex/cortex-foundation/compare/v4.0.8...v4.0.9
[v4.0.8]: https://github.com/rinvex/cortex-foundation/compare/v4.0.7...v4.0.8
[v4.0.7]: https://github.com/rinvex/cortex-foundation/compare/v4.0.6...v4.0.7
[v4.0.6]: https://github.com/rinvex/cortex-foundation/compare/v4.0.5...v4.0.6
[v4.0.5]: https://github.com/rinvex/cortex-foundation/compare/v4.0.4...v4.0.5
[v4.0.4]: https://github.com/rinvex/cortex-foundation/compare/v4.0.3...v4.0.4
[v4.0.3]: https://github.com/rinvex/cortex-foundation/compare/v4.0.2...v4.0.3
[v4.0.2]: https://github.com/rinvex/cortex-foundation/compare/v4.0.1...v4.0.2
[v4.0.1]: https://github.com/rinvex/cortex-foundation/compare/v4.0.0...v4.0.1
[v4.0.0]: https://github.com/rinvex/cortex-foundation/compare/v3.1.4...v4.0.0
[v3.1.4]: https://github.com/rinvex/cortex-foundation/compare/v3.1.3...v3.1.4
[v3.1.3]: https://github.com/rinvex/cortex-foundation/compare/v3.1.2...v3.1.3
[v3.1.2]: https://github.com/rinvex/cortex-foundation/compare/v3.1.1...v3.1.2
[v3.1.1]: https://github.com/rinvex/cortex-foundation/compare/v3.1.0...v3.1.1
[v3.1.0]: https://github.com/rinvex/cortex-foundation/compare/v3.0.3...v3.1.0
[v3.0.3]: https://github.com/rinvex/cortex-foundation/compare/v3.0.2...v3.0.3
[v3.0.2]: https://github.com/rinvex/cortex-foundation/compare/v3.0.1...v3.0.2
[v3.0.1]: https://github.com/rinvex/cortex-foundation/compare/v3.0.0...v3.0.1
[v3.0.0]: https://github.com/rinvex/cortex-foundation/compare/v2.2.5...v3.0.0
[v2.2.5]: https://github.com/rinvex/cortex-foundation/compare/v2.2.4...v2.2.5
[v2.2.4]: https://github.com/rinvex/cortex-foundation/compare/v2.2.3...v2.2.4
[v2.2.3]: https://github.com/rinvex/cortex-foundation/compare/v2.2.2...v2.2.3
[v2.2.2]: https://github.com/rinvex/cortex-foundation/compare/v2.2.1...v2.2.2
[v2.2.1]: https://github.com/rinvex/cortex-foundation/compare/v2.2.0...v2.2.1
[v2.2.0]: https://github.com/rinvex/cortex-foundation/compare/v2.1.2...v2.2.0
[v2.1.2]: https://github.com/rinvex/cortex-foundation/compare/v2.1.1...v2.1.2
[v2.1.1]: https://github.com/rinvex/cortex-foundation/compare/v2.1.0...v2.1.1
[v2.1.0]: https://github.com/rinvex/cortex-foundation/compare/v2.0.3...v2.1.0
[v2.0.3]: https://github.com/rinvex/cortex-foundation/compare/v2.0.2...v2.0.3
[v2.0.2]: https://github.com/rinvex/cortex-foundation/compare/v2.0.1...v2.0.2
[v2.0.1]: https://github.com/rinvex/cortex-foundation/compare/v2.0.0...v2.0.1
[v2.0.0]: https://github.com/rinvex/cortex-foundation/compare/v1.0.4...v2.0.0
[v1.0.4]: https://github.com/rinvex/cortex-foundation/compare/v1.0.3...v1.0.4
[v1.0.3]: https://github.com/rinvex/cortex-foundation/compare/v1.0.2...v1.0.3
[v1.0.2]: https://github.com/rinvex/cortex-foundation/compare/v1.0.1...v1.0.2
[v1.0.1]: https://github.com/rinvex/cortex-foundation/compare/v1.0.0...v1.0.1
[v1.0.0]: https://github.com/rinvex/cortex-foundation/compare/v0.0.2...v1.0.0
[v0.0.2]: https://github.com/rinvex/cortex-foundation/compare/v0.0.1...v0.0.2
