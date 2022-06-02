<?php

namespace JurianArie\UnauthorisedDetection\Tests\Fixtures;

use Illuminate\Routing\Controller;

class ControllerWithAuthorizeCall extends Controller
{
    public function index(): string
    {
        $this->authorize('do-stuff');

        return '';
    }
}
