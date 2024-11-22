<?php

namespace App\Classes\ServicesResponses;

class OperationResponseSuccess extends OperationResponse
{
    public function __construct(array $data = [])
    {
        parent::__construct(true, $data);
    }
}
