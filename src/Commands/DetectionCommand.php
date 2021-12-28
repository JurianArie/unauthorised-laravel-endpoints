<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection\Commands;

use Illuminate\Console\Command;
use JurianArie\UnauthorisedDetection\Detector;

class DetectionCommand extends Command
{
    public $signature = 'unauthorised-endpoints:detect';

    public $description = 'Detect unauthorised endpoints';

    public function handle(): int
    {
        $unauthorizedEndpoints = (new Detector())->unauthorizedEndpoints()
            ->pluck('action.controller');

        if ($unauthorizedEndpoints->isEmpty()) {
            $this->info('No unauthorised endpoints found!');

            return self::SUCCESS;
        }

        $this->warn('Unauthorised endpoints detected:');

        foreach ($unauthorizedEndpoints as $unauthorizedEndpoint) {
            $this->warn($unauthorizedEndpoint);
        }

        return self::FAILURE;
    }
}
