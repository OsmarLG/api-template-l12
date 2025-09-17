<?php

namespace App\Actions\User;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class GetUserByIdAction
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the get user by ID action
     *
     * @param int $id
     * @return User
     * @throws \Exception
     */
    public function execute(int $id): User
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new \Exception('Usuario no encontrado', 404);
        }

        return $user;
    }
}
