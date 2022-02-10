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

## Ignoring routes
You can ignore routes by adding the name, uri or action of the route to the `ignore` array in the config file.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
