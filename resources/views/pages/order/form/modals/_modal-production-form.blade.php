@php
    use App\Classes\Helpers\Db\CheckHelper;
    use App\Enums\Orders\ProductionStatusEnum;
    use App\Enums\Orders\ProductionStepsCountEnum;

    /**
     * @var App\Models\Check $stage
     */

    $stepsAvailable = CheckHelper::getAvailableStepsCountTotal($stage->checkAccepted);
    $latestProduction = $stage->checkAccepted->latestProduction;
@endphp

<x-modal id="production-form-modal">
    <x-slot:title>
        {{ __('productions.adding_production') }}
    </x-slot:title>

    <x-slot:buttons>
        <x-button id="production-save-button"
                  class="btn btn-secondary"
                  type="button"
                  :loadable="true"
        >{{ __('buttons.save') }}</x-button>
    </x-slot:buttons>

    <form id="production-form">
        @csrf

        <input type="hidden" name="id">
        <input type="hidden" name="check_id" value="{{ $stage->checkAccepted->id }}">

        <div class="form-group local-forms">
            @php
                $options = collect(ProductionStepsCountEnum::cases())->keyBy('value')->pluck('value', 'value')->filter(
                    fn(string $value) => $value === ProductionStepsCountEnum::NEED_MORE->value
                        || $stepsAvailable >= 15 || $value === ProductionStepsCountEnum::FIVE->value
                )->map(
                    fn(string $value) => $value === ProductionStepsCountEnum::NEED_MORE->value ? __('common.need_more') : $value
                );
            @endphp

            @if(isUserClinicalDirector())
                <x-form.input id="steps-count"
                              class="floating"
                              name="steps_count"
                              :label="__('checks.steps_count_with_max_number', ['number' => $stepsAvailable])"
                              type="number"
                              :max="$stepsAvailable"
                ></x-form.input>
            @else
                <x-form.select id="steps-count"
                               class="floating"
                               name="steps_count"
                               :label="__('checks.steps_count', ['number' => $stepsAvailable])"
                               :options="$options"
                ></x-form.select>
            @endif
        </div>

        @if(isUserClinicalDirector())
            <div class="form-group local-forms">
                <x-form.select id="status"
                               class="floating"
                               name="status"
                               :label="__('common.status')"
                               :options="ProductionStatusEnum::getTranslationMap('productions.status_enums')"
                ></x-form.select>
            </div>
        @endif

        <div class="form-group local-forms">
            <x-form.input id="production-term"
                          name="production_term"
                          type="number"
                          :label="__('productions.production_term')"
            ></x-form.input>
        </div>
    </form>
</x-modal>
