<?php
require_once __DIR__ . './FormRequest.php';

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
            'role'     => ['string'],
        ];
    }
}
