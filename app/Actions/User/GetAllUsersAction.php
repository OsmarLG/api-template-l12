<?php

namespace App\Actions\User;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class GetAllUsersAction
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the get all users action
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function execute(array $filters = []): LengthAwarePaginator
    {
        return $this->userRepository->paginate($filters);
    }
}
