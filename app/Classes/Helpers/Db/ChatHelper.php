<?php

namespace App\Classes\Helpers\Db;

use App\Enums\Chat\ChatContextEnum;
use App\Enums\Chat\ChatTypeEnum;
use App\Models\Chat;

class ChatHelper
{
    public static function title(Chat $chat): ?string
    {
        $contextTitle = match ($chat->context) {
            ChatContextEnum::ORDER->value => __('orders.order_number', ['number' => $chat->context_id])
        };

        $typeTitle = match ($chat->context) {
            ChatContextEnum::ORDER->value => match ($chat->type) {
                ChatTypeEnum::INTERNAL->value => __('Внутренний'),
                ChatTypeEnum::WITH_DOCTOR->value => isUserDoctor() ? null : __('С доктором'),
                default => null,
            }
        };

        return $contextTitle . ($typeTitle ? ' (' . $typeTitle . ')' : '');
    }
}
