<?php

namespace JurianArie\UnauthorisedDetection\Tests\Fixtures\Requests;

class FormRequestWithoutAuthorize extends \Illuminate\Foundation\Http\FormRequest
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
