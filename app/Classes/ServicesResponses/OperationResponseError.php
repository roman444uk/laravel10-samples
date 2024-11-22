<?php

namespace App\Classes\ServicesResponses;

class OperationResponseError extends OperationResponse
{
    public function __construct(string $message = null, array $errors = [], array $data = [])
    {
        parent::__construct(false, $data, $message, $errors);
    }
}
