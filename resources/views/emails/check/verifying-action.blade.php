@php
    use App\Classes\Helpers\Db\CheckVerifyingActionHelper;use App\Enums\Orders\CheckStatusEnum;use App\Enums\Orders\StageTabEnum;use App\Enums\Users\UserRoleEnum;use App\Models\Check;

    /**
     * @var Check $check
     * @var CheckStatusEnum|string $oldStatus
     * @var UserRoleEnum|string $recipientRole
     */
@endphp

{!! CheckVerifyingActionHelper::getEmailMessage($check, $oldStatus, $recipientRole) !!}

<p>
    <a href="{{ route('order.edit', ['order' => $check->stage->order, 'stage' => $check->stage, 'tab' => StageTabEnum::CHECK->value]) }}"
       target="_blank"
    >
        {{ __('checks.open_3d_check') }}
    </a>
</p>
