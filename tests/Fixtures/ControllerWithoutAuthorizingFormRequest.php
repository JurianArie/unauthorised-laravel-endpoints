<?php

namespace JurianArie\UnauthorisedDetection\Tests\Fixtures;

use Illuminate\Routing\Controller;
use JurianArie\UnauthorisedDetection\Tests\Fixtures\Requests\FormRequestWithoutAuthorize;

class ControllerWithoutAuthorizingFormRequest extends Controller
{
    public function index(FormRequestWithoutAuthorize $request): string
    {
        return '';
    }
}
