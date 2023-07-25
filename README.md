# Cortex Foundation

The core foundation of **Rinvex Cortex** modular application architecture.

[![Packagist](https://img.shields.io/packagist/v/cortex/foundation.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/cortex/foundation)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rinvex/cortex-foundation.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/rinvex/cortex-foundation/)
[![Travis](https://img.shields.io/travis/rinvex/cortex-foundation.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/rinvex/cortex-foundation)
[![StyleCI](https://styleci.io/repos/77746390/shield)](https://styleci.io/repos/77746390)
[![License](https://img.shields.io/packagist/l/cortex/foundation.svg?label=License&style=flat-square)](https://github.com/rinvex/cortex-foundation/blob/develop/LICENSE)


## Installation and Usage

This package should **NOT** be installed individually, it's required by [`rinvex/cortex`](https://github.com/rinvex/cortex) and requires a new laravel application instance with special architecture satisfied with **Rinvex Cortex**.

This package still not yet documented, but you can use it on your own responsibility.

To be documented soon..!

### Support Helpers

#### `intend()`

The `intend` method returns redirect response:
```php
intend([
    'route' => 'route.name.here',
    'withErrors' => ['error.message.id' => 'A custom error message'],
]);
```

> **Note:** this helper accepts `redirect` methods as it's input keys, such as `withErrors`, `with`, `back`, and `route` ..etc

### unique_with Validator Rule: Usage

Use it like any `Validator` rule:

```php
$rules = [
    '<field1>' => 'unique_with:<table>,<field2>[,<field3>,...,<ignore_rowid>]',
];
```

See the [Validation documentation](http://laravel.com/docs/validation) of Laravel.

#### Specify different column names in the database

If your input field names are different from the corresponding database columns, you can specify the column names
explicitly.

E.g. your input contains a field 'last_name', but the column in your database is called 'sur_name':

```php
$rules = [
    'first_name' => 'unique_with:users, middle_name, last_name = sur_name',
];
```

#### Ignore existing row (useful when updating)

You can also specify a row id to ignore (useful to solve unique constraint when updating)

This will ignore row with id 2:

```php
$rules = [
    'first_name' => 'required|unique_with:users,last_name,2',
    'last_name' => 'required',
];
```

To specify a custom column name for the id, pass it like:

```php
$rules = [
    'first_name' => 'required|unique_with:users,last_name,2 = custom_id_column',
    'last_name' => 'required',
];
```

If your id is not numeric, you can tell the validator:

```php
$rules = [
    'first_name' => 'required|unique_with:users,last_name,ignore:abc123',
    'last_name' => 'required',
];
```

#### Add additional clauses (e.g. when using soft deletes)

You can also set additional clauses. For example, if your model uses soft deleting then you can use the following code
to select all existing rows but marked as deleted

```php
$rules = [
    'first_name' => 'required|unique_with:users,last_name,deleted_at,2 = custom_id_column',
    'last_name' => 'required',
];
```

*Soft delete caveat:*

In Laravel 5 (tested on 5.5), if the validation is performed in form request class, field deleted_at is skipped, because
it's not send in request. To solve this problem, add 'deleted_at' => null to Your validation parameters in request
class., e.g.:

```php
protected function validationData()
{
    return array_merge($this->request->all(), [
        'deleted_at' => null
    ]);
}
```

#### Specify specific database connection to use

If we have a connection named `some-database`, we can enforce this connection (rather than the default) like this:

```php
$rules = [
    'first_name' => 'unique_with:some-database.users, middle_name, last_name',
];
```

E.g. pretend you have a `users` table in your database plus `User` model like this:

```php
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table) {
            $table->increments('id');

            $table->timestamps();

            $table->string('first_name');
            $table->string('last_name');

            $table->unique(['first_name', 'last_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
```

```php
<?php

class User extends Eloquent { }
```

Now you can validate a given `first_name`, `last_name` combination with something like this:

```php
Route::post('test', function() {
    $rules = [
        'first_name' => 'required|unique_with:users,last_name',
        'last_name' => 'required',
    ];

    $validator = Validator::make(Input::all(), $rules);

    if($validator->fails()) {
        return Redirect::back()->withErrors($validator);
    }

    $user = new User;
    $user->first_name = Input::get('first_name');
    $user->last_name = Input::get('last_name');
    $user->save();

    return Redirect::route('home')->with('success', 'User created!');
});
```

## Changelog

Refer to the [Changelog](CHANGELOG.md) for a full history of the project.


## Support

The following support channels are available at your fingertips:

- [Chat on Slack](https://bit.ly/rinvex-slack)
- [Help on Email](mailto:help@rinvex.com)
- [Follow on Twitter](https://twitter.com/rinvex)


## Contributing & Protocols

Thank you for considering contributing to this project! The contribution guide can be found in [CONTRIBUTING.md](CONTRIBUTING.md).

Bug reports, feature requests, and pull requests are very welcome.

- [Versioning](CONTRIBUTING.md#versioning)
- [Pull Requests](CONTRIBUTING.md#pull-requests)
- [Coding Standards](CONTRIBUTING.md#coding-standards)
- [Feature Requests](CONTRIBUTING.md#feature-requests)
- [Git Flow](CONTRIBUTING.md#git-flow)


## Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail to [help@rinvex.com](help@rinvex.com). All security vulnerabilities will be promptly addressed.


## About Rinvex

Rinvex is a software solutions startup, specialized in integrated enterprise solutions for SMEs established in Alexandria, Egypt since June 2016. We believe that our drive The Value, The Reach, and The Impact is what differentiates us and unleash the endless possibilities of our philosophy through the power of software. We like to call it Innovation At The Speed Of Life. That’s how we do our share of advancing humanity.


## License

This software is released under [The MIT License (MIT)](LICENSE).

(c) 2016-2022 Rinvex LLC, Some rights reserved.
