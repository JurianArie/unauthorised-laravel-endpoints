<?php

namespace JurianArie\UnauthorisedDetection\Tests\Fixtures;

use Illuminate\Foundation\Http\FormRequest;

class FormRequestWithoutAuthorize extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
