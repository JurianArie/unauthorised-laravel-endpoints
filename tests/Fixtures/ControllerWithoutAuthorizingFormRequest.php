<?php

namespace JurianArie\UnauthorisedDetection\Tests\Fixtures;

use Illuminate\Routing\Controller;

class ControllerWithoutAuthorizingFormRequest extends Controller
{
    public function index(FormRequestWithoutAuthorize $request): string
    {
        return '';
    }
}
