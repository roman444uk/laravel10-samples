<?php

namespace App\Models\Builders;

use App\Enums\Chat\ChatParticipantStatusEnum;
use Illuminate\Database\Eloquent\Builder;

class ChatBuilder extends Builder
{
    public function joinActiveParticipants(string $type = 'inner'): self
    {
        return $this->joinParticipants($type)
            ->whereIn('participants.status', [ChatParticipantStatusEnum::ACTIVE->value, ChatParticipantStatusEnum::READONLY->value]);
    }

    public function joinParticipants(string $type = 'inner'): self
    {
        return $this->join('chat_participants as participants', 'chats.id', '=', 'participants.chat_id', $type);
    }

    public function whereParticipant(int $userId, bool $onlyActive = true): self
    {
        return $this->when($userId, function (self $builder, int $userId) use($onlyActive) {
            $onlyActive ? $builder->joinActiveParticipants() : $builder->joinParticipants();

            $builder->where('participants.user_id', '=', $userId);
        });
    }

    public function whereContext(string $context, int $contextId = null): self
    {
        return $this->when($context, function (self $builder, string $context) use ($contextId) {
            return $this->where(function (Builder $builder) use ($context, $contextId) {
                $builder->where('context', $context);

                if ($contextId) {
                    $builder->where('context_id', $contextId);
                }
            });
        });
    }
}
