<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:50',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'O nome do material é obrigatório.',
            'name.string' => 'O nome do material deve ser um texto.',
            'name.max' => 'O nome do material não pode ter mais de 255 caracteres.',

            'description.string' => 'A descrição deve ser um texto válido.',

            'category.required' => 'A categoria do material é obrigatória.',
            'category.string' => 'A categoria deve ser um texto válido.',
            'category.max' => 'A categoria não pode ter mais de 50 caracteres.',
        ];
    }
}
