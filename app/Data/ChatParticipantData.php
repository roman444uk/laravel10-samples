<?php

namespace App\Data;

use App\Data\Transformers\CarbonToArrayTransformer;
use App\Models\ChatParticipant;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\WithTransformer;

class ChatParticipantData extends \Spatie\LaravelData\Data
{
    public function __construct(
        public ?int      $id,
        public ?int      $chatId,
        public ?int      $userId,
        public ?UserData $user,
        #[WithTransformer(CarbonToArrayTransformer::class)]
        public ?Carbon   $createdAt,
        #[WithTransformer(CarbonToArrayTransformer::class)]
        public ?Carbon   $updatedAt,
    )
    {
    }

    public static function fromModel(ChatParticipant $chatParticipant): self
    {
        return new self(
            $chatParticipant->id,
            $chatParticipant->chat_id,
            $chatParticipant->user_id,
            $chatParticipant->whenLoaded('user', fn(ChatParticipant $chatParticipant) => UserData::fromModel($chatParticipant->user)),
            $chatParticipant->created_at,
            $chatParticipant->created_at,
        );
    }
}
