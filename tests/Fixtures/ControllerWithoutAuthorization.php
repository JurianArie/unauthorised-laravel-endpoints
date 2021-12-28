<?php

namespace JurianArie\UnauthorisedDetection\Tests\Fixtures;

use Illuminate\Routing\Controller;

class ControllerWithoutAuthorization extends Controller
{
    public function index(): string
    {
        return '';
    }
}
