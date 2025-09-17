<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class IndexUserRequest extends FormRequest
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
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:50',
            'email' => 'sometimes|string|email|max:255',
            'created_from' => 'sometimes|date',
            'created_to' => 'sometimes|date|after_or_equal:created_from',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'username.string' => 'El nombre de usuario debe ser una cadena de texto.',
            'username.max' => 'El nombre de usuario no puede exceder 50 caracteres.',
            'email.string' => 'El email debe ser una cadena de texto.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.max' => 'El email no puede exceder 255 caracteres.',
            'created_from.date' => 'La fecha de inicio debe ser una fecha válida.',
            'created_to.date' => 'La fecha de fin debe ser una fecha válida.',
            'created_to.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'per_page.integer' => 'Los elementos por página deben ser un número entero.',
            'per_page.min' => 'Debe mostrar al menos 1 elemento por página.',
            'per_page.max' => 'No se pueden mostrar más de 100 elementos por página.',
            'page.integer' => 'La página debe ser un número entero.',
            'page.min' => 'La página debe ser mayor a 0.',
        ];
    }
}
