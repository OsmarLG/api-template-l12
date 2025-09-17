<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $userId = request()->route('id');

        return [
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'alpha_dash',
                'max:50',
                Rule::unique('users', 'username')->ignore($userId)
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'password' => 'sometimes|string|min:8|confirmed',
            'password_confirmation' => 'required_with:password|string|min:8',
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
            'name.required' => 'El nombre es requerido.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder 255 caracteres.',
            'username.required' => 'El nombre de usuario es requerido.',
            'username.string' => 'El nombre de usuario debe ser una cadena de texto.',
            'username.alpha_dash' => 'El nombre de usuario solo puede contener letras, números, guiones y guiones bajos.',
            'username.max' => 'El nombre de usuario no puede exceder 50 caracteres.',
            'username.unique' => 'Este nombre de usuario ya está registrado.',
            'email.required' => 'El email es requerido.',
            'email.string' => 'El email debe ser una cadena de texto.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.max' => 'El email no puede exceder 255 caracteres.',
            'email.unique' => 'Este email ya está registrado.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'password_confirmation.required_with' => 'La confirmación de contraseña es requerida cuando se proporciona una contraseña.',
            'password_confirmation.string' => 'La confirmación de contraseña debe ser una cadena de texto.',
            'password_confirmation.min' => 'La confirmación de contraseña debe tener al menos 8 caracteres.',
        ];
    }
}
