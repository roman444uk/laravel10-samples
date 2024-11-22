<?php

namespace App\Services\Db\System;

use Illuminate\Notifications\DatabaseNotification;

class NotificationService
{
    /**
     * Read notification.
     */
    public function read(DatabaseNotification $notification): void
    {
        $notification->update([
            'read_at' => now(),
//            'data->read_at' => now(),
        ]);
    }
}
