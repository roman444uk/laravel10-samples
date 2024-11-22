<?php

namespace App\Http\Controllers\Chat;

use App\Data\ChatData;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Order;
use App\Models\Repositories\ChatRepository;
use App\Services\Db\Chat\ChatService;
use App\Services\Db\Chat\ChatSyncService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(
        protected ChatRepository $chatRepository,
        protected ChatService $chatService
    )
    {
        $this->authorizeResource(Chat::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('pages.chat.index', [
            'chatsCollection' => $this->chatRepository->getUserChats(getUser()),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $chat)
    {
        $chat->loadParticipants()->loadMessages();

        $order = Order::firstWhere('id', $chat->context_id);

        app(ChatSyncService::class)->syncOrderChatInternalParticipants($chat, $order);
        app(ChatSyncService::class)->syncOrderChatWithDoctorParticipants($chat, $order);

        $this->chatRepository->applyNotViewedMessagesCount($chat, getUserId());

        return successJsonResponse(null, [
            'chat' => ChatData::fromModel($chat),
        ]);
    }

    public function markAsViewed(Chat $chat)
    {
        $this->chatService->markAsViewed($chat, getUserId());

        return successJsonResponse(null);
    }
}
