<?php

namespace App\Services;

use App\Actions\User\GetAllUsersAction;
use App\Actions\User\GetUserByIdAction;
use App\Actions\User\CreateUserAction;
use App\Actions\User\UpdateUserAction;
use App\Actions\User\DeleteUserAction;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService extends BaseService
{
    /**
     * Get all users with optional filtering and pagination
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAllUsers(array $filters = []): LengthAwarePaginator
    {
        return $this->callAction(GetAllUsersAction::class, $filters);
    }

    /**
     * Get a specific user by ID
     *
     * @param int $id
     * @return User
     * @throws \Exception
     */
    public function getUserById(int $id): User
    {
        return $this->callAction(GetUserByIdAction::class, $id);
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        return $this->callAction(CreateUserAction::class, $data);
    }

    /**
     * Update an existing user
     *
     * @param int $id
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function updateUser(int $id, array $data): User
    {
        return $this->callAction(UpdateUserAction::class, $id, $data);
    }

    /**
     * Delete a user
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteUser(int $id): bool
    {
        return $this->callAction(DeleteUserAction::class, $id);
    }
}
