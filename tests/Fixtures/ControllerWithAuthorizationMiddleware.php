<?php

namespace JurianArie\UnauthorisedDetection\Tests\Fixtures;

use Illuminate\Routing\Controller;

class ControllerWithAuthorizationMiddleware extends Controller
{
    public function __construct()
    {
        $this->middleware(['can:do-stuff']);
    }

    public function __invoke(): string
    {
        return '';
    }
}
