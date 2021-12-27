<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection;

use Illuminate\Support\ServiceProvider;

class UnauthorisedDetectionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/unauthorized-detection.php' => config_path('unauthorized-detection.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/unauthorized-detection.php', 'unauthorized-detection');
    }
}
