<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\User\IndexUserRequest;
use App\Http\Requests\User\ShowUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\DeleteUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends ApiController
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get all users
     * 
     * Get all users with optional filtering and pagination support.
     * Supports filtering by name, email, and creation date range.
     *
     * @response UserCollection
     */
    public function index(IndexUserRequest $request): JsonResponse
    {
        return $this->handleRequest(function () use ($request) {
            $users = $this->userService->getAllUsers($request->validated());
            return new UserCollection($users);
        }, 'Usuarios obtenidos exitosamente');
    }

    /**
     * Get a specific user
     * 
     * Retrieve detailed information for a specific user by their ID.
     * Returns user data including name, email, and timestamps.
     *
     * @response UserResource
     */
    public function show(ShowUserRequest $request, int $id): JsonResponse
    {
        return $this->handleRequest(function () use ($id) {
            $user = $this->userService->getUserById($id);
            return new UserResource($user);
        }, 'Usuario obtenido exitosamente');
    }

    /**
     * Create a new user
     * 
     * Create a new user account with the provided information.
     * Requires name, email, and password. Email must be unique.
     *
     * @response UserResource
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        return $this->handleRequest(function () use ($request) {
            $user = $this->userService->createUser($request->validated());
            return new UserResource($user);
        }, 'Usuario creado exitosamente', 201);
    }

    /**
     * Update an existing user
     * 
     * Update user information including name, email, and optionally password.
     * Email must be unique if changed. Password is optional for updates.
     *
     * @response UserResource
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        return $this->handleRequest(function () use ($request, $id) {
            $user = $this->userService->updateUser($id, $request->validated());
            return new UserResource($user);
        }, 'Usuario actualizado exitosamente');
    }

    /**
     * Delete a user
     * 
     * Permanently delete a user account from the system.
     * This action cannot be undone. Returns success confirmation.
     *
     * @response 200 {"status": true, "message": "Usuario eliminado exitosamente", "errors": [], "data": null}
     */
    public function destroy(DeleteUserRequest $request, int $id): JsonResponse
    {
        return $this->handleRequest(function () use ($id) {
            $this->userService->deleteUser($id);
            return null;
        }, 'Usuario eliminado exitosamente');
    }
}
