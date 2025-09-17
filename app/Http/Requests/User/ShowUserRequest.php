<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ShowUserRequest extends FormRequest
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
            // Validation is handled in prepareForValidation
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $userId = request()->route('id');
        
        // Validate that the user exists
        if (!$userId || !\App\Models\User::find($userId)) {
            abort(404, 'Usuario no encontrado');
        }
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'id.required' => 'El ID del usuario es requerido.',
            'id.integer' => 'El ID del usuario debe ser un número entero.',
            'id.exists' => 'El usuario especificado no existe.',
        ];
    }
}
