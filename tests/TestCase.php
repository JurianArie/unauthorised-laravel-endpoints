<?php

namespace JurianArie\UnauthorisedDetection\Tests;

use JurianArie\UnauthorisedDetection\UnauthorisedDetectionServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [UnauthorisedDetectionServiceProvider::class];
    }
}
