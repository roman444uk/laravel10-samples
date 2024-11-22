<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Stage;
use App\Services\Db\Chat\ChatMessageService;
use App\Services\Db\Chat\ChatQuickMessageService;
use Illuminate\Http\Request;

class ChatQuickMessageController extends Controller
{
    public function __construct(
        protected ChatMessageService      $chatMessageService,
        protected ChatQuickMessageService $chatQuickMessageService,
    )
    {
    }

    /**
     * Message from doctor with problem.
     */
    public function haveProblem(Request $request, Order $order)
    {
        $validated = $request->validate([
            'message' => ['required', 'string'],
        ], [
            'message.required' => __('chats.validation.message.required')
        ]);

        return operationJsonResponse($this->chatQuickMessageService->haveProblem($order, $validated['message']));
    }

    /**
     * Requiring new production.
     */
    public function productionRequireNext(Stage $stage)
    {
        return operationJsonResponse($this->chatQuickMessageService->productionRequireNext($stage));
    }
}
