<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'El login (email o username) es requerido.',
            'login.string' => 'El login debe ser una cadena de texto.',
            'password.required' => 'La contraseña es requerida.',
            'password.string' => 'La contraseña debe ser una cadena de texto.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ];
    }
}
