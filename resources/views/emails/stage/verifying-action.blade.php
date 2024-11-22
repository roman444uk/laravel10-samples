@php
    use App\Classes\Helpers\Db\StageVerifyingActionHelper;
    use App\Enums\NotifySourceEnum;
    use App\Enums\Orders\CheckStatusEnum;
    use App\Enums\Orders\StageTabEnum;
    use App\Enums\Users\UserRoleEnum;
    use App\Models\Stage;

    /**
     * @var Stage $stage
     * @var CheckStatusEnum|string $oldStatus
     * @var UserRoleEnum|string $recipientRole
     */
@endphp

{!! StageVerifyingActionHelper::getSource($stage, $oldStatus, NotifySourceEnum::EMAIL_BODY->value , $recipientRole, true) !!}

<p>
    <a href="{{ route('order.edit', ['order' => $stage->order, 'stage' => $stage, 'tab' => StageTabEnum::CHECK->value]) }}"
       target="_blank"
    >
        {{ __('checks.open_3d_check') }}
    </a>
</p>
