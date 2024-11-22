@php
    use App\Enums\Orders\CheckStatusEnum;use App\Models\Check;use App\Models\Stage;

    /**
     * @var bool $stageEditionDisabled
     * @var Stage $stage
     */
@endphp

<div class="text-end">
    @can('createAny', Check::class)
        <x-button type="button"
                  class="btn btn-primary submit-form me-2"
                  id="check-add-button"
                  data-bs-toggle="modal"
                  data-bs-target="#check-form-modal"
                  fa-icon="plus"
                  :disabled="!can('create', [Check::class, $stage])"
        >
            {{ __ ('buttons.add') }}
        </x-button>
    @endcan

    @include('pages.check.index._table', [
        'checksCollection' => $stage->checks->filter(function(Check $check) {
            return !isUserDoctor() || in_array($check->status, [
                CheckStatusEnum::VERIFICATION_BY_DOCTOR->value,
                CheckStatusEnum::REJECTED_BY_DOCTOR->value,
                CheckStatusEnum::ACCEPTED->value,
            ]);
        }),
    ])
</div>
