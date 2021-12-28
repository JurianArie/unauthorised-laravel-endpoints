<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection;

use Illuminate\Console\Command;
use Illuminate\Routing\Route;

class DetectionCommand extends Command
{
    public $signature = 'unauthorised-endpoints:detect';

    public $description = 'Detect unauthorised endpoints';

    /**
     * Execute the console command.
     *
     * @param \JurianArie\UnauthorisedDetection\Detector $detector
     *
     * @return int
     */
    public function handle(Detector $detector): int
    {
        $unauthorizedEndpoints = $detector
            ->unauthorizedEndpoints()
            ->map(function (Route $route): string {
                $action = $route->getAction('controller') ?? $route->getName();

                return is_string($action) ? $action : '';
            });

        if ($unauthorizedEndpoints->isEmpty()) {
            $this->info('No unauthorised endpoints found!');

            return self::SUCCESS;
        }

        $this->warn('Unauthorised endpoints detected:');

        $unauthorizedEndpoints->each(function (string $unauthorizedEndpoint): void {
            $this->warn($unauthorizedEndpoint);
        });

        return self::FAILURE;
    }
}
