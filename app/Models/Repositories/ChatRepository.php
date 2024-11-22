<?php

namespace App\Models\Repositories;

use App\Data\ChatData;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\ChatMessageView;
use App\Models\ChatParticipant;
use App\Models\User;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;

class ChatRepository
{
    /**
     * Returns `Chat` by parameters or creates new one.
     */
    public function firstOrCreate(ChatData $chatData): Chat
    {
        return Chat::firstOrCreate([
            'context' => $chatData->context,
            'context_id' => $chatData->contextId,
            'type' => $chatData->type,
        ]);
    }

    /**
     * Creates and returns new `Participant` on `Chat` model.
     */
    public function createParticipant(Chat $chat, int $userId): ChatParticipant
    {
        return $chat->participants()->create([
            'chat_id' => $chat->id,
            'user_id' => $userId,
        ]);
    }

    /**
     * Returns `Chat` collection for user.
     */
    public function getUserChats(User $user): SupportCollection
    {
        $chatsCollection = Chat::whereParticipant($user->id)
            ->select('chats.*')
            ->with([
                'participants.user.profile.fileAvatar', 'messages', 'lastMessage'
            ])
            ->get();

        return $this->applyNotViewedMessagesCount($chatsCollection, $user->id);
    }

    /**
     * Applies `not_viewed_messages_count` attribute value to chat model or collection
     */
    public function applyNotViewedMessagesCount(SupportCollection|Chat $chatsCollection, $userId): SupportCollection|Chat
    {
        if ($chatsCollection instanceof SupportCollection) {
            return $this->applyNotViewedMessagesCountToCollection($chatsCollection, $userId);
        }

        $chatsCollection = $this->applyNotViewedMessagesCountToCollection(collect([$chatsCollection]), $userId);

        return $chatsCollection->get(0);
    }

    /**
     * Apply `not_viewed_messages_count` attribute to each model within collection.
     */
    public function applyNotViewedMessagesCountToCollection(SupportCollection $chatsCollection, $userId): SupportCollection
    {
        $rows = DB::table('chat_messages as messages')
            ->select(['messages.chat_id', DB::raw('count(messages.id) - count(views.id) as not_viewed')])
            ->leftJoin('chat_message_views as views', function (JoinClause $join) use ($userId) {
                $join->on('views.chat_message_id', '=', 'messages.id');
                $join->on('views.user_id', '=', DB::raw($userId));
            })
            ->whereIn('messages.chat_id', $chatsCollection->pluck('id')->toArray())
            ->whereNot('messages.user_id', $userId)
            ->where('views.id', null)
            ->groupBy('messages.chat_id')
            ->get()
            ->keyBy('chat_id');

        return $chatsCollection->map(function (Chat $chat) use ($rows) {
            $chat->not_viewed_messages_count = $rows[$chat->id]->not_viewed ?? 0;

            return $chat;
        });
    }

    /**
     * Marks all chat messages as viewed.
     */
    public function markAsViewed(Chat $chat, $userId): void
    {
        ChatMessage::select('chat_messages.*')
            ->join('chat_message_views as views', function (JoinClause $join) use ($userId) {
                $join->on('views.chat_message_id', '=', 'chat_messages.id');
                $join->on('views.user_id', '=', DB::raw($userId));
            }, null, null, 'left')
            ->where('chat_messages.chat_id', $chat->id)
            ->whereNot('chat_messages.user_id', $userId)
            ->whereNull('views.id')
            ->each(function (ChatMessage $chatMessage) use ($userId) {
                ChatMessageView::firstOrCreate([
                    'chat_message_id' => $chatMessage->id,
                    'user_id' => $userId,
                ]);
            });
    }
}
