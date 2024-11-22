@php
    use App\Enums\Orders\Stage\StageWideningDueToEnum;use App\Enums\Orders\Stage\StageWideningEnum;use App\Enums\Orders\Stage\StageWideningVolumeEnum;

    /**
     * @var string $prefixForId
     * @var string $prefixForName
     * @var App\Models\Stage $stage
     * @var string $title
     */
@endphp

<div class="row">
    <div class="{{ $titleClass }}">
        {{ $title }}
    </div>

    <div @class(['col4 col-md-4 col-xl-4'])>
        @foreach(StageWideningEnum::getTranslationMap('stages.widening_enums') as $value => $label)
            <x-stage.form-radio :id="$prefixForId.'value-' . $value"
                                :name="$prefixForName . '[value]'"
                                :value="$value"
                                :label="$label"
                                :checked="$stage->fieldsEqual('widening.value', $value)"
                                :disabled="$stageEditionDisabled"
            ></x-stage.form-radio>
        @endforeach
    </div>

    @php
        $subRowDisabled = $stage->fieldsNotExists('widening.value', [
            StageWideningEnum::FIVE_FIVE->value, StageWideningEnum::SEVEN_SEVEN->value,
            StageWideningEnum::SIX_SIX->value, StageWideningEnum::THREE_THREE->value,
        ]);
    @endphp
    <div @class(['input-block col4 col-md-4 col-xl-4', $rightColumnClass])>
        <div class="row">
            <div @class(['col6 col-md-6 col-lg-6 col-xl-6 stage-sub-row', 'disabled' => $subRowDisabled])
                 data-sub-row="widening-due-to-top"
            >
                <label class="col-form-label">
                    {{ __('stages.top_dentition_due_to') }}
                </label>
                @foreach(StageWideningDueToEnum::getTranslationMap('stages.widening_due_to_enums') as $value => $label)
                    <x-stage.form-checkbox :id="$prefixForId.'due-to-top-' . $value"
                                           :name="$prefixForName . '[due_to][top]['.$value.']'"
                                           :value="$value"
                                           :label="$label"
                                           :checked="$stage->fieldsExists('widening.due_to.top', $value) && !$subRowDisabled"
                                           :disabled="$stageEditionDisabled || $subRowDisabled"
                    ></x-stage.form-checkbox>
                @endforeach
            </div>

            <div @class(['col6 col-md-6 col-lg-6 col-xl-6 stage-sub-row', 'disabled' => $subRowDisabled])
                 data-sub-row="widening-due-to-bottom"
            >
                <label class="col-form-label">
                    {{ __('stages.bottom_dentition_due_to') }}
                </label>
                @foreach(StageWideningDueToEnum::getTranslationMap('stages.widening_due_to_enums') as $value => $label)
                    <x-stage.form-checkbox :id="$prefixForId.'due-to-bottom-' . $value"
                                           :name="$prefixForName . '[due_to][bottom]['.$value.']'"
                                           :value="$value"
                                           :label="$label"
                                           :checked="$stage->fieldsExists('widening.due_to.bottom', $value) && !$subRowDisabled"
                                           :disabled="$stageEditionDisabled || $subRowDisabled"
                    ></x-stage.form-checkbox>
                @endforeach
            </div>
        </div>

        <div @class(['stage-sub-row', 'disabled' => $subRowDisabled]) data-sub-row="widening-volume">
            <label class="col-form-label">
                {{ __('stages.dental_arch_expansion_volume') }}
            </label>
            @foreach(StageWideningVolumeEnum::getTranslationMap('stages.widening_volume_enums') as $value => $label)
                <x-stage.form-radio :id="$prefixForId.'volume-' . $value"
                                    :name="$prefixForName . '[volume]'"
                                    :value="$value"
                                    :label="$label"
                                    :checked="$stage->fieldsEqual('widening.volume', $value) && !$subRowDisabled"
                                    :disabled="$stageEditionDisabled || $subRowDisabled"
                ></x-stage.form-radio>
            @endforeach
        </div>
    </div>
</div>
