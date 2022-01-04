<?php

declare(strict_types=1);

namespace JurianArie\UnauthorisedDetection;

use Illuminate\Foundation\Console\RouteListCommand;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use UnexpectedValueException;

class DetectionCommand extends RouteListCommand
{
    // Here for Laravel 6.x compatibility.
    public const SUCCESS = 0;
    public const FAILURE = 1;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'unauthorised-endpoints:detect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detect unauthorised endpoints';

    /**
     * The detector instance.
     *
     * @var \JurianArie\UnauthorisedDetection\Detector
     */
    protected Detector $detector;

    /**
     * Create a new detection command instance.
     *
     * @param \Illuminate\Routing\Router $router
     * @param \JurianArie\UnauthorisedDetection\Detector $detector
     *
     * @return void
     */
    public function __construct(Router $router, Detector $detector)
    {
        parent::__construct($router);

        $this->detector = $detector;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $routes = $this->getRoutes();

        if (empty($routes)) {
            $this->info('All endpoints are authorised.');

            return self::SUCCESS;
        }

        $this->error('Unauthorised endpoints found.');

        $this->displayRoutes($routes);

        return self::FAILURE;
    }

    /**
     * Compile the routes into a displayable format.
     *
     * @return string[]
     */
    protected function getRoutes(): array
    {
        $routes = $this->detector
            ->unauthorizedEndpoints()
            ->map(function (Route $route): ?array {
                return $this->getRouteInformation($route);
            })
            ->filter()
            ->all();

        $sort = $this->option('sort');

        if (!is_string($sort)) {
            throw new UnexpectedValueException('Sort option must be a string.');
        }

        if ($sort !== 'precedence') {
            $routes = $this->sortRoutes($sort, $routes);
        }

        if ($this->option('reverse')) {
            $routes = array_reverse($routes);
        }

        return $this->pluckColumns($routes);
    }
}
