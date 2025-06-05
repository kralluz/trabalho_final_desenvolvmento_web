<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreAdSenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string|max:1000',
            'codigo_adsense' => 'required|string|max:2000',
            'posicao' => 'required|in:header,sidebar,footer,content',
            'ativo' => 'sometimes|boolean'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'titulo.required' => 'O título é obrigatório',
            'titulo.max' => 'O título não pode ter mais de 255 caracteres',
            'descricao.required' => 'A descrição é obrigatória',
            'descricao.max' => 'A descrição não pode ter mais de 1000 caracteres',
            'codigo_adsense.required' => 'O código AdSense é obrigatório',
            'codigo_adsense.max' => 'O código AdSense não pode ter mais de 2000 caracteres',
            'posicao.required' => 'A posição é obrigatória',
            'posicao.in' => 'A posição deve ser: header, sidebar, footer ou content',
            'ativo.boolean' => 'O campo ativo deve ser verdadeiro ou falso'
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'sucesso' => false,
                'mensagem' => 'Dados de entrada inválidos',
                'erros' => $validator->errors()
            ], 422)
        );
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (!$this->has('ativo')) {
            $this->merge(['ativo' => true]);
        }
    }
}
