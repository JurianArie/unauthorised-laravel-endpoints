# Detecting unauthorized routes

[![CI](https://github.com/JurianArie/unauthorised-laravel-endpoints/actions/workflows/ci.yml/badge.svg)](https://github.com/JurianArie/unauthorised-laravel-endpoints/actions/workflows/ci.yml)

It's easy to forget authorization. This package is here to help you out!

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
* Authorization via source code (This has some [limitations](#limitations).)

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
By default, only the `auth` middleware is checked. **Routes without the specified middleware will be ignored.**
```php
'authentication-middleware' => [
    'auth:api', // Only check api.
],
```

### Specify authorization middleware
Here you can specify the middleware that is used to authorize the routes.
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
You can ignore routes the same way as with `Request::is()` and `Request::routeIs()` https://laravel.com/docs/9.x/requests#inspecting-the-request-path.

## Adding custom detection
You can add custom detection by adding a class that implements `\JurianArie\UnauthorisedDetection\Detectors\DetectsAuthorization` to the `'authorization-detectors'` array in your config.

## Limitations
You might get false positives if your authorization has to be detected in your source code.

* Your action doesn't have any source code.
* Your authorization happens further down in the call stack.
* Your authorization uses structures such as `abort_if($user->cannot(...)`, `if ($user->cannot(...)) {...}`

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
