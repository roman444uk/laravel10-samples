@php
    use App\Classes\Helpers\Db\StageStatusHelper;
    use App\Enums\Orders\StageStatusEnum;
    use App\Support\Str;

    $statuses = collect(StageStatusHelper::getStatusCasesChronological(getUserRole()));
    $selectedIndex = StageStatusHelper::getStatusIndexChronological($stage->status, getUserRole());
@endphp

<div class="html-template" id="swal-stage-statuses">
    <div class="step-line-box step-line-box-stage-statues">
        <ul class="step-line-list">
            @foreach($statuses as $index => $status)
                <x-list.steps-item @class([
                    'active' => $index < $selectedIndex,
                    'active-last' => $status->value === $statuses->get($selectedIndex)->value,
                ])>
                <span class="title">
                    {{ StageStatusHelper::getStatusLabel($status->value, getUserRole()) }}
                </span>
                    <span class="desc">
                    {{ StageStatusHelper::getStatusDescriptionLabel($status->value, getUserRole()) }}
                </span>
                </x-list.steps-item>
            @endforeach
        </ul>
    </div>

</div>
