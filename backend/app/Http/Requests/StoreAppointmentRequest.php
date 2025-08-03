<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
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
        $minDate = Carbon::now()->startOfDay()->addWeekdays(2)->format('Y-m-d');

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:50'],
            'neighborhood' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'suggested_date' => ['required', 'date', 'after_or_equal:' . $minDate],
            'phone' => ['required', 'string', 'regex:/^\(?\d{2}\)?[\s-]?\d{4,5}-?\d{4}$/'],
            'email' => ['nullable', 'email'],
            'observation' => ['nullable'],
            'material_id' => ['required', 'array', 'min:1'],
            'material_id.*' => ['exists:materials,id'],
        ];
    }
    public function messages(): array
    {
        return [
            'full_name.required' => 'Nome é obrigatório!',
            'full_name.string' => 'Nome não deve possuir caracteres especiais ou numeros',
            'suggested_date.after_or_equal' => 'A data deve ser pelo menos 2 dias úteis após a data atual.',
            'material_id.required' => 'É necessário selecionar ao menos um tipo de material reciclável.',
            'material_id.*.exists' => 'Um ou mais tipos de materiais selecionados são inválidos.',
            'phone.regex' => 'Formato de telefone inválido.',
        ];
    }
}
