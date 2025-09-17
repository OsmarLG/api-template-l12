<?php

namespace App\Services;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\RegisterAction;
use App\Actions\Auth\ForgotPasswordAction;
use App\Actions\Auth\ResetPasswordAction;

class AuthService extends BaseService
{
    /**
     * Login using email or username and returns [user, token]
     *
     * @param array{login:string,password:string} $credentials
     * @return array{user: \App\Models\User, token: string}
     */
    public function login(array $credentials): array
    {
        return $this->callAction(LoginAction::class, $credentials);
    }

    /**
     * Register a new user and return [user, token]
     *
     * @param array{name:string,username:string,email:string,password:string} $data
     * @return array{user: \App\Models\User, token: string}
     */
    public function register(array $data): array
    {
        return $this->callAction(RegisterAction::class, $data);
    }

    /**
     * Send password reset link
     *
     * @param array{email:string} $data
     * @return array{status:string}
     */
    public function forgotPassword(array $data): array
    {
        return $this->callAction(ForgotPasswordAction::class, $data);
    }

    /**
     * Reset password using token
     *
     * @param array{email:string,token:string,password:string,password_confirmation?:string} $data
     * @return array{status:string}
     */
    public function resetPassword(array $data): array
    {
        return $this->callAction(ResetPasswordAction::class, $data);
    }
}
