<?php

namespace App\Services\Db\Users;

use App\Classes\ServicesResponses\OperationResponse;
use App\Data\AddressData;
use App\Models\Address;
use App\Models\Doctor;
use App\Models\DoctorAddress;
use App\Services\Db\System\AddressService;

class DoctorAddressService
{
    public function __construct(
        protected AddressService $addressService
    )
    {
    }

    /**
     * Creates relation between doctor and address.
     */
    public function firstOrCreate(Doctor $doctor, Address $address): DoctorAddress
    {
        return DoctorAddress::firstOrCreate([
            'doctor_id' => $doctor->id,
            'address_id' => $address->id,
        ]);
    }

    /**
     * Creates new doctor clinic relation with raw clinic data
     */
    public function createFromRawAddressData(Doctor $doctor, AddressData $addressData): OperationResponse
    {
        $address = $this->addressService->findOrCreate($addressData)->get('address');

        $doctorAddress = $this->firstOrCreate($doctor, $address);

        return successOperationResponse([
            'address' => $address,
            'doctorAddress' => $doctorAddress
        ]);
    }
}
