<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Password;

class ForgotPasswordAction
{
    /**
     * Send password reset link to user's email.
     *
     * @param array{email:string} $data
     * @return array{status:string}
     */
    public function execute(array $data): array
    {
        $status = Password::sendResetLink(['email' => $data['email']]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new \Exception(__($status), 422);
        }

        return ['status' => __($status)];
    }
}
