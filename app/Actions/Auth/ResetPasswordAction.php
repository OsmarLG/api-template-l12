<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordAction
{
    /**
     * Reset user's password using token.
     *
     * @param array{email:string,token:string,password:string} $data
     * @return array{status:string}
     */
    public function execute(array $data): array
    {
        $status = Password::reset(
            [
                'email' => $data['email'],
                'token' => $data['token'],
                'password' => $data['password'],
                'password_confirmation' => $data['password_confirmation'] ?? $data['password'],
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw new \Exception(__($status), 422);
        }

        return ['status' => __($status)];
    }
}
