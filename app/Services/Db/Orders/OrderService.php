<?php

namespace App\Services\Db\Orders;

use App\Classes\ServicesResponses\OperationResponse;
use App\Classes\ServicesResponses\OperationResponseError;
use App\Data\OrderData;
use App\Models\Doctor;
use App\Models\Order;
use App\Models\Repositories\OrderRepository;
use App\Models\Repositories\UserRepository;
use App\Models\User;
use App\Notifications\Orders\OrderCreatedNotification;
use App\Services\Db\Chat\ChatService;

class OrderService
{
    public function __construct(
        protected ChatService     $chatService,
        protected OrderRepository $orderRepository,
        protected StageService    $stageService,
        protected UserRepository  $userRepository,
    )
    {
    }

    /**
     * Creates new `Order` model.
     */
    public function store(OrderData $orderData, Doctor $doctor): OperationResponse
    {
        $orderData->doctorId = $doctor->id;

        $order = $this->orderRepository->create($orderData);

        if (!$order || !$order->wasRecentlyCreated) {
            return OperationResponse::error();
        }

        $this->stageService->storeTreatment($order);

        $this->chatService->getOrderChatWithDoctor($order);
        $this->chatService->getOrderChatInternal($order);

//        getOrderEmployees($order)->each(function (User $user) use ($order) {
//            $user->notify(new OrderCreatedNotification($user, $order));
//        });

        return successOperationResponse([
            'order' => $order,
        ]);
    }

    /**
     * Updates an `Order` model.
     */
    public function update(Order $order, OrderData $orderData): OperationResponse
    {
        if (!$this->orderRepository->update($order, $orderData)) {
            return new OperationResponseError();
        }

        if ($orderData->stages) {
            $this->stageService->updateBulk($orderData->stages);
        }

        return successOperationResponse([
            'order' => $order
        ]);
    }

    /**
     * Destroys `Order` model.
     */
    public function destroy(Order $order): OperationResponse
    {
//        $orderService = $this;
//
//        if (!$order->delete()) {
//            return errorOperationResponse();
//        }
//
//        $order->stages->each(function(Stage $stage, ) use ($orderService) {
//            $orderService->stageService->destroy($stage);
//        });

        return successOperationResponse();
    }
}
