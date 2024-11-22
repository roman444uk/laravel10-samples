<?php

namespace App\Services\Db\Users;

use App\Classes\ServicesResponses\OperationResponse;
use App\Mail\RegisterConfirmationCodeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthService
{
    public function sendRegisterEmailConfirmationCode(string $email): OperationResponse
    {
        $code = Str::random(6);

        $success = Mail::send(new RegisterConfirmationCodeEmail($email, $code));

        if (!$success) {
            return errorOperationResponse();
        }

        return successOperationResponse([
            'code' => $code,
        ]);
    }
}
