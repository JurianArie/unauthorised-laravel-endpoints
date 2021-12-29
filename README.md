# Detecting unauthorized routes

Forgetting authorization is a common mistake. This package helps you to detect those mistakes.

## Installation

You can install the package via composer:

```bash
composer require jurianarie/unauthorised-laravel-endpoints
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="unauthorized-detection"
```

## Usage

```bash
php artisan unauthorised-endpoints:detect
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
