<?php

namespace App\Actions\User;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UpdateUserAction
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the update user action
     *
     * @param int $id
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function execute(int $id, array $data): User
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new \Exception('Usuario no encontrado', 404);
        }

        $updateData = [
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
        ];

        if (isset($data['password'])) {
            $updateData['password'] = bcrypt($data['password']);
        }

        return $this->userRepository->update($user, $updateData);
    }
}
