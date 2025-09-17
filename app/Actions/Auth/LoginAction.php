<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginAction
{
    /**
     * Execute login by email or username and return user and token.
     *
     * @param array{login:string,password:string} $credentials
     * @return array{user: User, token: string}
     * @throws \Exception
     */
    public function execute(array $credentials): array
    {
        $login = $credentials['login'] ?? '';
        $password = $credentials['password'] ?? '';

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        /** @var User|null $user */
        $user = User::where($field, $login)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw new \Exception('Credenciales inválidas', 401);
        }

        // Optionally, you can check if user is soft-deleted
        if (method_exists($user, 'trashed') && $user->trashed()) {
            throw new \Exception('La cuenta ha sido deshabilitada', 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
