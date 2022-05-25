# Detecting unauthorized routes

[![CI](https://github.com/JurianArie/unauthorised-laravel-endpoints/actions/workflows/ci.yml/badge.svg)](https://github.com/JurianArie/unauthorised-laravel-endpoints/actions/workflows/ci.yml)

Forgetting authorization is a common mistake. This package helps you to detect those mistakes.

## Installation

You can install the package via composer:

```bash
composer require jurianarie/unauthorised-laravel-endpoints --dev
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="unauthorized-detection"
```

## How it works
This package looks through all routes defined in your application and tries to find authorization checks.

There are a few build in checks:

* Authorization via middleware
* Authorization via FormRequests
* Authorization via source code (This has some [limitations](#known-limitations).)

## Usage

```bash
php artisan unauthorised-endpoints:detect
```

Or exclude vendor routes:

```bash
php artisan unauthorised-endpoints:detect --except-vendor
```


> Tip: the same arguments are available as with [route:list](https://laravel.com/docs/9.x/routing#the-route-list)

## Configuration

### Specify authentication middleware
By default, any middleware that starts with auth will be used. **Routes that don't require authentication will be ignored.**
```php
'authentication-middleware' => [
    'auth:api', // Only check api.
],
```

### Specify authorization middleware
```php
'authorization-middleware' => [
    'your-custom-middleware',
],
```

### Specify authorization methods
You can add regular expressions.
```php
'authorization-methods' => [
    '/\$this->authorize\(\'(.*)\\)/',
    '/Gate::authorize\(\'(.*)\\)/',
],
```

### Ignoring routes
You can ignore routes using `Request::is()`, `Request::routeIs()` https://laravel.com/docs/9.x/requests#inspecting-the-request-path.

Additionally, you can ignore route actions. For example:
```php
'ignore' => [
    '\App\Http\Controllers\ExampleController@show',
    '\App\Http\Controllers\InvokableController',
],
```

## Adding custom detection
You can add custom detection classes if you have more advanced requirements.

First implement a class that implements the `\JurianArie\UnauthorisedDetection\Detectors\DetectsAuthorization`.

Next add the class to the `'authorization-detectors'` array in the config file.


## Known limitations
You might get false positives if your authorization has to be detected in your source code.

* Your action doesn't have any source code.
* Your authorization happens further down in the call stack.
* Your authorization uses structures such as `abort_if($user->cannot(...)`, `if ($user->cannot(...)) {...}`

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
