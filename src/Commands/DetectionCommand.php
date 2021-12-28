<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection\Commands;

use Illuminate\Console\Command;
use JurianArie\UnauthorisedDetection\Detector;

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
