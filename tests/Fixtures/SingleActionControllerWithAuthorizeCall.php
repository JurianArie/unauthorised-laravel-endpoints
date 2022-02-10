<?php

namespace JurianArie\UnauthorisedDetection\Tests\Fixtures;

use Illuminate\Routing\Controller;

class SingleActionControllerWithAuthorizeCall extends Controller
{
    public function __invoke(): string
    {
        $this->authorize('do-stuff');

        return '';
    }
}
