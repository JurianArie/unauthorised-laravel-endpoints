<?php

namespace JurianArie\UnauthorisedDetection\Tests\Fixtures;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;

class ControllerWithGateCall extends Controller
{
    public function index(): string
    {
        Gate::authorize('do-stuff');

        return '';
    }
}
