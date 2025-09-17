<?php

namespace App\Actions\User;

use App\Repositories\Contracts\UserRepositoryInterface;

class DeleteUserAction
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the delete user action
     *
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function execute(int $id): bool
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new \Exception('Usuario no encontrado', 404);
        }

        return $this->userRepository->delete($user);
    }
}
