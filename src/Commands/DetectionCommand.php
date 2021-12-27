<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection\Commands;

use Illuminate\Console\Command;

class DetectionCommand extends Command
{
    public $signature = 'unauthorised-endpoints:detect';

    public $description = 'Detect unauthorised endpoints';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
