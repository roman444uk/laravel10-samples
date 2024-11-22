@php
    use App\Enums\Orders\Stage\StageSagittalPlaneCorrectionByteJumpOffsetEnum;use App\Enums\Orders\Stage\StageSagittalPlaneCorrectionEnum;use App\Enums\Orders\Stage\StageSagittalPlaneCorrectionGroupVergareTypeEnum;

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

    <div @class(['input-block col8 col-md-8 col-xl-8', $leftColumnClass])>
        @foreach(StageSagittalPlaneCorrectionEnum::getTranslationMap('stages.sagittal_plane_correction_enums') as $value => $label)
            <x-stage.form-radio :id="$prefixForId.'value-' . $value"
                                :name="$prefixForName . '[value]'"
                                :value="$value"
                                :label="$label"
                                :checked="$stage->fieldsEqual('sagittal_plane_correction.value', $value)"
                                :disabled="$stageEditionDisabled"
            ></x-stage.form-radio>

            @if($value === StageSagittalPlaneCorrectionEnum::BYTE_JUMP_OFFSET->value)
                @php
                    $subRowDisabled = $stage->fieldsNotEqual('sagittal_plane_correction.value', StageSagittalPlaneCorrectionEnum::BYTE_JUMP_OFFSET->value);
                @endphp
                <div @class(['row ps-lg-5 stage-sub-row', 'disabled' => $subRowDisabled])
                     data-sub-row="sagittal-plane-correction-byte-jump-offset"
                >
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <span class="custom-badge status-red fs-6">
                            {{ __('stages.sagittal_plane_correction_byte_jump_offset_note') }}
                        </span>
                    </div>
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        @foreach(StageSagittalPlaneCorrectionByteJumpOffsetEnum::getTranslationMap('stages.sagittal_plane_correction_byte_jump_offset_enums') as $value => $label)
                            <x-stage.form-checkbox :id="$prefixForId.'byte-jump-offset-' . $value"
                                                   :name="$prefixForName . '[byte_jump_offset]['.$value.']'"
                                                   :value="$value"
                                                   :label="$label"
                                                   :checked="$stage->fieldsExists('sagittal_plane_correction.byte_jump_offset', $value) && !$subRowDisabled"
                                                   :disabled="$stageEditionDisabled || $subRowDisabled"
                            ></x-stage.form-checkbox>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($value === StageSagittalPlaneCorrectionEnum::DISTALIZATION->value)
                @php
                    $subRowDisabled = $stage->fieldsNotEqual('sagittal_plane_correction.value', StageSagittalPlaneCorrectionEnum::DISTALIZATION->value);
                @endphp
                <div class="col-md-12 col-lg-12 col-xl-12">
                    <span class="custom-badge status-red fs-6">
                        {{ __('stages.sagittal_plane_correction_distalization_note') }}
                    </span>
                </div>
                <div @class(['row ps-lg-5 stage-sub-row', 'disabled' => $subRowDisabled])
                     data-sub-row="sagittal-plane-correction-distalization"
                >
                    @foreach(['sequential', 'mixed_sequential', 'group_vergare'] as $distalizationType)
                        <div class="col-md-5 col-lg-5 col-xl-5 pb-2  pt-2">
                            {{ __('stages.distalization_' . $distalizationType) }}
                        </div>
                        <div class="col-md-3 col-lg-3 col-xl-3 pb-2  pt-2">
                            <div class="row">
                                @foreach([1, 2, 3, 4] as $side)
                                    <div @class(['col-md-6 col-lg-6 col-xl-6 jaw-side-ord-' . $side])>
                                        <x-stage.form-checkbox
                                            :id="$prefixForId.'distalization-' . implode('-', [$distalizationType, $side])"
                                            :name="$prefixForName . '[distalization]['.$distalizationType.']['.$side.']'"
                                            :value="1"
                                            :label="$side"
                                            :checked="$stage->fieldsChecked('sagittal_plane_correction.distalization.' . implode('.', [$distalizationType, $side]))  && !$subRowDisabled"
                                            :disabled="$stageEditionDisabled || $subRowDisabled"
                                        ></x-stage.form-checkbox>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="row ps-lg-5">
                        <div class="col12 col-md-12 col-lg-12 col-xl-12">
                            @foreach(StageSagittalPlaneCorrectionGroupVergareTypeEnum::getTranslationMap('stages.sagittal_plane_correction_distalization_group_vergare_type_enums') as $value => $label)
                                <x-stage.form-radio :id="$prefixForId.'distalization-group-vergare-type-' . $value"
                                                    :name="$prefixForName . '[distalization][group_vergare][type]'"
                                                    :value="$value"
                                                    :label="$label"
                                                    :checked="$stage->fieldsEqual('sagittal_plane_correction.distalization.group_vergare.type', $value) && !$subRowDisabled"
                                                    :disabled="$stageEditionDisabled || $subRowDisabled"
                                ></x-stage.form-radio>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if($value === StageSagittalPlaneCorrectionEnum::MESIALIZATION->value)
                @php
                    $subRowDisabled = $stage->fieldsNotEqual('sagittal_plane_correction.value', StageSagittalPlaneCorrectionEnum::MESIALIZATION->value);
                @endphp
                <div @class(['row ps-lg-5 stage-sub-row', 'disabled' => $subRowDisabled])
                     data-sub-row="sagittal-plane-correction-mesialization"
                >
                    @foreach(['sequential', 'closing_gaps', 'group_in_sectors'] as $mesializationType)
                        <div class="col-md-5 col-lg-5 col-xl-5 pb-2 pt-2">
                            {{ __('stages.mesialization_' . $mesializationType) }}
                            @if($mesializationType === 'closing_gaps')
                                <span class="custom-badge status-red fs-6">
                                    {{ __('stages.sagittal_plane_correction_mesialization_closing_gaps_note') }}
                                </span>
                            @endif
                        </div>

                        <div class="col-md-3 col-lg-3 col-xl-3 pb-2  pt-2">
                            <div class="row">
                                @foreach([1, 2, 3, 4] as $side)
                                    <div @class(['col-md-6 col-lg-6 col-xl-6 jaw-side-ord-' . $side])>
                                        <x-stage.form-checkbox
                                            :id="$prefixForId.'mesialization-' . implode('-', [$mesializationType, $side])"
                                            :name="$prefixForName . '[mesialization]['.$mesializationType.']['.$side.']'"
                                            :value="1"
                                            :label="$side"
                                            :checked="$stage->fieldsChecked('sagittal_plane_correction.mesialization.' . implode('.', [$mesializationType, $side]))"
                                            :disabled="$stageEditionDisabled"
                                        ></x-stage.form-checkbox>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>
</div>
