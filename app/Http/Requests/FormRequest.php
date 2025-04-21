<?php
require_once __DIR__ . './JsonResponse.php';

abstract class FormRequest
{
    abstract public function rules(): array;

    public function validated(): array
    {
        $rules = $this->rules();
        $data  = [];

        foreach ($rules as $field => $checks) {
            $value = $_REQUEST[$field] ?? null;
            if (in_array('required', $checks, true) && empty($value) && !isset($_FILES[$field])) {
                (new JsonResponse(["error" => "Field {$field} is required"], 400))->send();
            }
            if (isset($_FILES[$field])) {
                $data[$field] = $_FILES[$field];
                continue;
            }
            if ($value !== null) {
                if (in_array('numeric', $checks, true) && !is_numeric($value)) {
                    (new JsonResponse(["error" => "Field {$field} must be numeric"], 400))->send();
                }
                if (in_array('email', $checks, true) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    (new JsonResponse(["error" => "Field {$field} must be a valid email"], 400))->send();
                }
                $data[$field] = $value;
            }
        }

        return $data;
    }
}
