<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Pipelines\RegisterUserPipeline;

class RegisterAction
{
    protected RegisterUserPipeline $pipeline;

    public function __construct(RegisterUserPipeline $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    /**
     * Register a new user using the pipeline and return user and token.
     *
     * @param array{name:string,username:string,email:string,password:string} $data
     * @return array{user: User, token: string}
     */
    public function execute(array $data): array
    {
        $user = $this->pipeline->execute($data);
        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
