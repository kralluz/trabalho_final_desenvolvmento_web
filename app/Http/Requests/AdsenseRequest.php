<?php
require_once __DIR__ . './FormRequest.php';

class AdsenseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'       => ['required', 'string'],
            'description' => ['required', 'string'],
            'price'       => ['required', 'numeric'],
        ];
    }
}
