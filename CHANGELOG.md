# Cortex Foundation Change Log

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](CONTRIBUTING.md).


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
