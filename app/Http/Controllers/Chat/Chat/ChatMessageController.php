<?php

namespace App\Http\Controllers\Chat;

use App\Data\ChatMessageData;
use App\Enums\System\FileOwnerEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\ChatMessageRequest;
use App\Models\ChatMessage;
use App\Models\File;
use App\Services\Db\Chat\ChatMessageService;
use App\Services\Db\Chat\ChatService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChatMessageController extends Controller
{
    public function __construct(
        protected ChatService        $chatService,
        protected ChatMessageService $chatMessageService,
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChatMessageRequest $request)
    {
        return operationJsonResponse(
            $this->chatMessageService->store(ChatMessageData::fromRequest($request)),
            ['message']
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ChatMessageRequest $request, ChatMessage $chatMessage)
    {
        return operationJsonResponse(
            $this->chatMessageService->update($chatMessage, ChatMessageData::fromRequest($request)),
            ['message']
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatMessage $chatMessage)
    {
        return operationJsonResponse($this->chatMessageService->destroy($chatMessage));
    }

    /**
     * Destroys file of specific chat message.
     */
    public function destroyFile(File $file)
    {
        if ($file->owner !== FileOwnerEnum::CHAT_MESSAGE->value || $file->user_id !== getUserId()) {
            throw new NotFoundHttpException();
        }

        return operationJsonResponse(
            $this->chatMessageService->destroyFile($file),
            ['message']
        );
    }
}
