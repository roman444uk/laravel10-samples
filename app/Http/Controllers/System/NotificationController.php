<?php

namespace App\Http\Controllers\System;

use App\Data\Filters\NotificationDataFilter;
use App\Enums\SortOrderEnum;
use App\Models\Repositories\NotificationRepository;
use App\Services\Db\System\NotificationService;
use App\Traits\Controllers\Paginatable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class NotificationController extends BaseController
{
    use AuthorizesRequests,
        Paginatable,
        ValidatesRequests;

    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(FormRequest $request, NotificationRepository $notificationRepository): View|JsonResponse
    {
        $notificationFilter = NotificationDataFilter::fromRequest($request)
            ->notifiableId(getUserId())
            ->sort(SortOrderEnum::DESC->value)
            ->pageSize($this->getPageSize(null, $request));

        return view('pages.notification.index', [
            'notificationsCollection' => $notificationRepository->paginate($notificationFilter),
        ]);
    }

    /**
     * Mark notification as read notification and return unread notifications count.
     */
    public function read(DatabaseNotification $notification, NotificationRepository $notificationRepository): View|JsonResponse
    {
        $this->notificationService->read($notification);

        return successJsonResponse(null, [
            'unreadNotificationsCount' => $notificationRepository->getUnreadNotificationCount(getUserId()),
        ]);
    }
}
