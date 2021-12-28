<?php

namespace JurianArie\UnauthorisedDetection\Tests\Fixtures;

use Illuminate\Routing\Controller;
use JurianArie\UnauthorisedDetection\Tests\Fixtures\Requests\FormRequestWithAuthorize;

class ControllerWithAuthorizingFormRequest extends Controller
{
    public function index(FormRequestWithAuthorize $request): string
    {
        return '';
    }
}
