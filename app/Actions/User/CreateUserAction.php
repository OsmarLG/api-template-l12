<?php

namespace App\Actions\User;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class CreateUserAction
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the create user action
     *
     * @param array $data
     * @return User
     */
    public function execute(array $data): User
    {
        $userData = [
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ];

        return $this->userRepository->create($userData);
    }
}
