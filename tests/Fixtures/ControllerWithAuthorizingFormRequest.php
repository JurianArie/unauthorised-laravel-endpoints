<?php

namespace JurianArie\UnauthorisedDetection\Tests\Fixtures;

use Illuminate\Routing\Controller;

class ControllerWithAuthorizingFormRequest extends Controller
{
    public function index(FormRequestWithAuthorize $request): string
    {
        return '';
    }
}
