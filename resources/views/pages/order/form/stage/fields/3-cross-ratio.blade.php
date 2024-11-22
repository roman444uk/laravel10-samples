@php
    use App\Enums\Orders\Stage\StageCrossRatioEnum;

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

    <div @class(['input-block col4 col-md-4 col-xl-4', $leftColumnClass])>
        <x-stage.form-radio :id="$prefixForId.'value-yes'"
                            :name="$prefixForName . '[value]'"
                            value="1"
                            :label="__('buttons.yes')"
                            :checked="$stage->fieldsChecked('cross_ratio.value')"
                            :disabled="$stageEditionDisabled"
        ></x-stage.form-radio>
        <x-stage.form-radio :id="$prefixForId.'value-no'"
                            :name="$prefixForName . '[value]'"
                            value="0"
                            :label="__('buttons.no')"
                            :checked="$stage->fieldsUnchecked('cross_ratio.value')"
                            :disabled="$stageEditionDisabled"
        ></x-stage.form-radio>
    </div>

    @php
        $subRowDisabled = $stage->fieldsEmptyOrUnchecked('cross_ratio.value');
    @endphp
    <div
            @class(['input-block col4 col-md-4 col-xl-4 stage-sub-row', 'disabled' => $subRowDisabled, $rightColumnClass])
            data-sub-row="cross-ratio-types"
    >
        @foreach(StageCrossRatioEnum::getTranslationMap('stages.cross_ratio_enums') as $value => $label)
            <x-stage.form-checkbox :id="$prefixForId.'types-' . $value"
                                   :name="$prefixForName . '[types]['.$value.']'"
                                   :value="$value"
                                   :label="$label"
                                   :checked="$stage->fieldsExists('cross_ratio.types', $value) && !$subRowDisabled"
                                   :disabled="$stageEditionDisabled || $subRowDisabled"
            ></x-stage.form-checkbox>
        @endforeach
    </div>
</div>
