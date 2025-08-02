<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAppointmentStatusRequest extends FormRequest
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
    public function rules()
    {
        return [
            'status' => ['required', Rule::in(['Pendente', 'Agendado', 'Concluído', 'Cancelado'])],
            'observation' => [
                'required_if:status,Concluído,Cancelado',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages()
    {
        return [
            'observation.required_if' => 'A observação é obrigatória ao concluir ou cancelar o agendamento.',
        ];
    }
}
