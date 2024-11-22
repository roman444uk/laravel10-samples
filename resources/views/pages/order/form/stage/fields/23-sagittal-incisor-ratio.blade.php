@php
    use App\Enums\Orders\Stage\StageSagittalIncisorRatioChangeEnum;use App\Enums\Orders\Stage\StageSagittalIncisorRatioEnum;
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
        <label class="col-form-label">
            {{ __('stages.top_jaw') }}
        </label>

        @foreach(StageSagittalIncisorRatioEnum::getTranslationMap('stages.sagittal_incisor_ratio_enums') as $value => $label)
            <x-stage.form-radio :id="$prefixForId.'top-value-' . $value"
                                :name="$prefixForName . '[top][value]'"
                                :value="$value"
                                :label="$label"
                                :checked="$stage->fieldsEqual('sagittal_incisor_ratio.top.value', $value)"
                                :disabled="$stageEditionDisabled"
            ></x-stage.form-radio>

            @php
                $subRowDisabled = $stage->fieldsNotEqual('sagittal_incisor_ratio.top.value', StageSagittalIncisorRatioEnum::CHANGE->value);
            @endphp
            @if($value === StageSagittalIncisorRatioEnum::CHANGE->value)
                <div class="row ps-lg-5">
                    <div @class(['col12 col-md-12 col-lg-12 col-xl-12 stage-sub-row', 'disabled' => $subRowDisabled])
                         data-sub-row="sagittal-incisor-ratio-top-change"
                    >
                        @foreach(StageSagittalIncisorRatioChangeEnum::getTranslationMap('stages.sagittal_incisor_ratio_change_enums') as $value => $label)
                            <x-stage.form-checkbox :id="$prefixForId.'top-change-' . $value"
                                                   :name="$prefixForName . '[top][change]['.$value .']'"
                                                   :value="$value"
                                                   :label="$label"
                                                   :checked="$stage->fieldsExists('sagittal_incisor_ratio.top.change', $value) && !$subRowDisabled"
                                                   :disabled="$stageEditionDisabled || $subRowDisabled"
                            ></x-stage.form-checkbox>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <div @class(['input-block col4 col-md-4 col-xl-4', $rightColumnClass])>
        <label class="col-form-label">
            {{ __('stages.bottom_jaw') }}
        </label>

        @foreach(StageSagittalIncisorRatioEnum::getTranslationMap('stages.sagittal_incisor_ratio_enums') as $value => $label)
            <x-stage.form-radio :id="$prefixForId.'bottom-value-' . $value"
                                :name="$prefixForName . '[bottom][value]'"
                                :value="$value"
                                :label="$label"
                                :checked="$stage->fieldsEqual('sagittal_incisor_ratio.bottom.value', $value)"
                                :disabled="$stageEditionDisabled"
            ></x-stage.form-radio>

            @if($value === StageSagittalIncisorRatioEnum::CHANGE->value)
                @php
                    $subRowDisabled = $stage->fieldsNotEqual('sagittal_incisor_ratio.bottom.value', StageSagittalIncisorRatioEnum::CHANGE->value);
                @endphp
                <div class="row ps-lg-5">
                    <div @class(['col12 col-md-12 col-lg-12 col-xl-12 stage-sub-row', 'disabled' => $subRowDisabled])
                         data-sub-row="sagittal-incisor-ratio-bottom-change"
                    >
                        @foreach(StageSagittalIncisorRatioChangeEnum::getTranslationMap('stages.sagittal_incisor_ratio_change_enums') as $value => $label)
                            <x-stage.form-checkbox :id="$prefixForId.'bottom-change-' . $value"
                                                   :name="$prefixForName . '[bottom][change]['.$value .']'"
                                                   :value="$value"
                                                   :label="$label"
                                                   :checked="$stage->fieldsExists('sagittal_incisor_ratio.bottom.change', $value) && !$subRowDisabled"
                                                   :disabled="$stageEditionDisabled || $subRowDisabled"
                            ></x-stage.form-checkbox>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>

