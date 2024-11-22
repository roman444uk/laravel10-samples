<?php

namespace App\Services\Db\System;

use App\Classes\ServicesResponses\OperationResponse;
use App\Data\AddressData;
use App\Models\Address;

class AddressService
{
    /**
     * Creates new clinic record.
     */
    public function findOrCreate(AddressData $addressData): OperationResponse
    {
        $address = Address::firstWhere([
            'address' => $addressData->address,
        ]);

        if (!$address) {
            $address = Address::create([
                'address' => $addressData->address,
                'data' => $addressData->data,
            ]);
        }

        if (!$address) {
            return errorOperationResponse();
        }

        return successOperationResponse([
            'address' => $address
        ]);
    }
}
