@php
    use App\Enums\Orders\Stage\StageLateralCrossRatioAdjustEnum;use App\Enums\Orders\Stage\StageLateralCrossRatioEnum;

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

    <div @class(['col4 col-md-4 col-xl-4', $leftColumnClass])>
        @foreach(StageLateralCrossRatioEnum::getTranslationMap('stages.cross_ratio_lateral_enums') as $value => $label)
            <x-stage.form-radio :id="$prefixForId.'value-' . $value"
                                :name="$prefixForName . '[value]'"
                                :value="$value"
                                :label="$label"
                                :checked="$stage->fieldsEqual('cross_ratio_lateral.value', $value)"
                                :disabled="$stageEditionDisabled"
            ></x-stage.form-radio>

            @if($value === StageLateralCrossRatioEnum::ADJUST->value)
                @php
                    $subRowDisabled = $stage->fieldsNotEqual('cross_ratio_lateral.value', StageLateralCrossRatioEnum::ADJUST->value);
                @endphp
                <div class="row ps-lg-5">
                    <div
                        @class(['col12 col-md-12 col-lg-12 col-xl-12 stage-sub-row', 'disabled' => $subRowDisabled])
                        data-sub-row="cross-ratio-lateral"
                    >
                        @foreach(StageLateralCrossRatioAdjustEnum::getTranslationMap('stages.cross_ratio_lateral_adjust_enums') as $value => $label)
                            <x-stage.form-checkbox :id="$prefixForId.'adjust-' . $value"
                                                   :name="$prefixForName . '[adjust][' . $value . ']'"
                                                   :value="$value"
                                                   :label="$label"
                                                   :checked="$stage->fieldsExists('cross_ratio_lateral.adjust', $value) && !$subRowDisabled"
                                                   :disabled="$subRowDisabled"
                                                   :disabled="$stageEditionDisabled"
                            ></x-stage.form-checkbox>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
