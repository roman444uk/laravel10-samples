<?php

namespace App\Services\Db\Chat;

use App\Classes\ServicesResponses\OperationResponse;
use App\Data\ChatMessageData;
use App\Enums\Chat\ChatContextEnum;
use App\Enums\Chat\ChatMessageFileTypeEnum;
use App\Enums\Chat\ChatMessageTypeEnum;
use App\Events\Chat\ChatMessageCreatedEvent;
use App\Events\Chat\ChatMessageDestroyedEvent;
use App\Events\Chat\ChatMessageUpdatedEvent;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\File;
use App\Models\Repositories\ChatMessageRepository;
use App\Notifications\Orders\OrderChatNewMessageNotification;
use App\Services\Db\System\FileService;

class ChatMessageService
{
    public function __construct(
        protected ChatMessageRepository $chatMessageRepository,
        protected ChatService           $chatService,
        protected FileService           $fileService,
    )
    {
    }

    /**
     * Returns chat with laboratory and creates if not yet exists.
     */
    public function store(ChatMessageData $chatMessageData): OperationResponse
    {
        /** @var Chat $chat */
        $chat = $chatMessageData->chatId ? Chat::firstWhere('id', $chatMessageData->chatId) : null;

        $chatMessageData->type = $chatMessageData->type ?? ChatMessageTypeEnum::USUAL->value;

        $chatMessage = $this->chatMessageRepository->create($chatMessageData);

        if ($chatMessageData->files) {
            $this->fileService->storeUploadedFiles(
                $chatMessageData->files,
                $chatMessage,
                ChatMessageFileTypeEnum::FILE->value,
                getUserId()
            );
        }

        $chatMessage->load('files');

        ChatMessageCreatedEvent::dispatch($chatMessage);

        if ($chat->context === ChatContextEnum::ORDER->value) {
            $this->sendOrderNotifications($chat, $chatMessage);
        }

        return successOperationResponse([
            'message' => ChatMessageData::fromModel($chatMessage),
        ]);
    }

    /**
     * Updates an `Check` model.
     */
    public function update(ChatMessage $chatMessage, ChatMessageData $chatMessageData): OperationResponse
    {
        if (!$this->chatMessageRepository->update($chatMessage, $chatMessageData)) {
            return errorOperationResponse();
        }

        $chatMessage->load(['repliedMessage', 'files']);

        ChatMessageUpdatedEvent::dispatch($chatMessage);

        return successOperationResponse([
            'message' => $chatMessage,
        ]);
    }

    public function sendOrderNotifications(Chat $chat, ChatMessage $chatMessage): void
    {
        if ($chatMessage->type === ChatMessageTypeEnum::SYSTEM->value) {
            return;
        }

        if (isUserDoctor($chatMessage->user)) {
            $chat->participants->each(function (ChatParticipant $participant) use ($chatMessage) {
                // send notification to employees
                if (isUserEmployee($participant->user)) {
                    $participant->user->notify(new OrderChatNewMessageNotification(
                        $participant->user,
                        $chatMessage,
                        OrderChatNewMessageNotification::DOCTOR,
                        OrderChatNewMessageNotification::EMPLOYEE
                    ));
                }
            });
        }

        if (isUserEmployee($chatMessage->user)) {
            $chat->participants->each(function (ChatParticipant $participant) use ($chatMessage) {
                // send notification to doctor
                if (isUserDoctor($participant->user)) {
                    $participant->user->notify(new OrderChatNewMessageNotification(
                        $participant->user,
                        $chatMessage,
                        OrderChatNewMessageNotification::EMPLOYEE,
                        OrderChatNewMessageNotification::DOCTOR,
                    ));
                }

                // send notification to other employees
                if (isUserEmployee($participant->user) && $participant->user->id !== $chatMessage->user->id) {
                    $participant->user->notify(new OrderChatNewMessageNotification(
                        $participant->user,
                        $chatMessage,
                        OrderChatNewMessageNotification::EMPLOYEE,
                        OrderChatNewMessageNotification::EMPLOYEE
                    ));
                }
            });
        }
    }

    /**
     * Remove chat message and all related data.
     */
    public function destroy(ChatMessage $chatMessage): OperationResponse
    {
        $chatMessageService = $this;
        $chatMessage->files->each(function (File $file) use ($chatMessageService) {
            $chatMessageService->fileService->destroy($file);
        });

        $chatMessage->delete();

        ChatMessageDestroyedEvent::dispatch($chatMessage);

        return successOperationResponse([
            'message' => ChatMessageData::fromModel($chatMessage),
        ]);
    }

    /**
     * Destroys file of specific chat message.
     */
    public function destroyFile(File $file): OperationResponse
    {
        $this->fileService->destroy($file);

        /** @var ChatMessage $chatMessage */
        $chatMessage = ChatMessage::firstWhere('id', $file->owner_id);

        $chatMessage->load('files');

        if (!$chatMessage->message && $chatMessage->files->count() === 0) {
            return $this->destroy($chatMessage);
        }

        ChatMessageUpdatedEvent::dispatch($chatMessage);

        return successOperationResponse([
            'message' => ChatMessageData::fromModel($chatMessage),
        ]);
    }
}
