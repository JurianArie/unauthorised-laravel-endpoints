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

## Usage

```bash
php artisan unauthorised-endpoints:detect
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
    '\App\Http\Controllers\ExampleController@index',
    '\App\Http\Controllers\InvokableController',
],
```

## Adding custom detection
You can add custom detection classes if you have more advanced requirements.

First implement a class that implements the `\JurianArie\UnauthorisedDetection\Detectors\DetectsAuthorization`.

Next add the class to the `'authorization-detectors'` array in the config file.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
