<?php

namespace App\Models\Repositories;

use App\Data\Filters\NotificationDataFilter;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationRepository
{
    public function getUnreadNotificationCount(int $notifiableId)
    {
        return DatabaseNotification::where([
            'notifiable_id' => $notifiableId,
        ])->whereNull('read_at')->count();
//        return DatabaseNotification::where([
//            'notifiable_id' => $notifiableId,
//        ])->whereRaw("data not like '%read_at%'")->count();
    }

    public function paginate(NotificationDataFilter $notificationFilter): LengthAwarePaginator
    {
        return DatabaseNotification::where([
            'notifiable_id' => $notificationFilter->notifiableId
        ])
            ->orderBy('created_at', $notificationFilter->sort ?? 'desc')
            ->paginate($notificationFilter->pageSize);
    }
}
