<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\AuthResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends ApiController
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login
     *
     * Autenticación con email o username.
     * Devuelve el usuario autenticado y el token de acceso.
     *
     * @response AuthResource
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->handleRequest(function () use ($request) {
            $payload = $this->authService->login($request->validated());
            return new AuthResource($payload);
        }, 'Autenticación exitosa');
    }

    /**
     * Register
     *
     * Registro de usuario con username, email y password.
     * Devuelve el usuario creado y el token.
     *
     * @response AuthResource
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->handleRequest(function () use ($request) {
            $payload = $this->authService->register($request->validated());
            return new AuthResource($payload);
        }, 'Registro exitoso', 201);
    }

    /**
     * Forgot Password
     *
     * Envía un enlace de restablecimiento de contraseña al email.
     *
     * @response {"status": true, "message": "Correo de restablecimiento enviado"}
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        return $this->handleRequest(function () use ($request) {
            return $this->authService->forgotPassword($request->validated());
        }, 'Correo de restablecimiento enviado');
    }

    /**
     * Reset Password
     *
     * Restablece la contraseña usando el token de recuperación.
     *
     * @response {"status": true, "message": "Contraseña restablecida correctamente"}
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        return $this->handleRequest(function () use ($request) {
            return $this->authService->resetPassword($request->validated());
        }, 'Contraseña restablecida correctamente');
    }
}
