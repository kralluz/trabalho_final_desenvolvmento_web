<?php
require_once __DIR__ . './FormRequest.php';

class ImageUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => ['required'],
        ];
    }
}
