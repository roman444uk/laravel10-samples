<?php

namespace App\Models\Repositories;

use App\Classes\Helpers\Db\StageHelper;
use App\Data\Filters\OrderDataFilter;
use App\Data\OrderData;
use App\Models\Builders\OrderBuilder;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository
{
    public function create(OrderData $orderData): ?Order
    {
        return Order::create([
            'doctor_id' => $orderData->doctorId,
            'patient_id' => $orderData->patientId,
            'clinic_id' => $orderData->clinicId,
            'product_type' => $orderData->productType,
        ]);
    }

    public function update(Order $order, OrderData $orderData): bool
    {
        $order->fill([
            'doctor_id' => $orderData->doctorId || $order->doctor_id,
            'patient_id' => $orderData->patientId,
            'clinic_id' => $orderData->clinicId,
            'product_type' => $orderData->productType,
        ]);

        return $order->save();
    }

    public function paginate(OrderDataFilter $orderFilter, array|string $with = null): LengthAwarePaginator
    {
        /** @var OrderBuilder $builder */
        $builder = Order::with('patient');

        if ($with) {
            $builder->with($with);
        }

        return $builder
            ->select('orders.*')
            ->leftJoinDoctors()
            ->leftJoinDoctorsUsers()
            ->leftJoinStages()
//            ->stageStatusIn(StageHelper::getStatusesOfAvailableOrders($orderFilter->requesterRole)->toArray())
            ->doctorId($orderFilter->doctorId)
            ->clientManagerId($orderFilter->clientManagerId)
            ->clinicalSpecialistId($orderFilter->clinicalSpecialistId)
            ->modeler3dId($orderFilter->modeler3dId)
            ->technicianDigitizerId($orderFilter->technicianDigitizerId)
            ->technicianProductionId($orderFilter->technicianProductionId)
            ->logisticManagerId($orderFilter->logisticManagerId)
            ->search($orderFilter->search)
            ->orderBy($orderFilter->sort ?? 'created_at', $orderFilter->desc ?? 'desc')
            ->paginate($orderFilter->pageSize);
    }
}

