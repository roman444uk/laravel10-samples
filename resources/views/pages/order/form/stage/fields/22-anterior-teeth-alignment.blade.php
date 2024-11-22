@php
    use App\Enums\Orders\Stage\StageAnteriorTeethAlignmentAlongCuttingEdgeEnum;use App\Enums\Orders\Stage\StageAnteriorTeethAlignmentEnum;

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
            {{ __('stages.anterior_top_teeth_alignment') }}
        </label>

        @foreach(StageAnteriorTeethAlignmentEnum::getTranslationMap('stages.anterior_teeth_alignment_enums') as $value => $label)
            <x-stage.form-radio :id="$prefixForId.'top-value-' . $value"
                                :name="$prefixForName . '[top][value]'"
                                :value="$value"
                                :label="$label"
                                :checked="$stage->fieldsEqual('anterior_teeth_alignment.top.value', $value)"
                                :disabled="$stageEditionDisabled"
            ></x-stage.form-radio>

            @if($value === StageAnteriorTeethAlignmentEnum::ALONG_CUTTING_EDGE->value)
                @php
                    $subRowDisabled = $stage->fieldsNotEqual('anterior_teeth_alignment.top.value', StageAnteriorTeethAlignmentEnum::ALONG_CUTTING_EDGE->value);
                @endphp
                <div class="row ps-lg-5">
                    <div @class(['col12 col-md-12 col-lg-12 col-xl-12 stage-sub-row', 'disabled' => $subRowDisabled])
                         data-sub-row="anterior-teeth-alignment-top-along-cutting-edge"
                    >
                        @foreach(StageAnteriorTeethAlignmentAlongCuttingEdgeEnum::getTranslationMap('stages.anterior_teeth_alignment_along_cutting_edge_enums') as $value => $label)
                            <x-stage.form-radio :id="$prefixForId.'top-along-cutting-edge-' . $value"
                                                :name="$prefixForName . '[top][along_cutting_edge]'"
                                                :value="$value"
                                                :label="$label"
                                                :checked="$stage->fieldsEqual('anterior_teeth_alignment.top.along_cutting_edge', $value) && !$subRowDisabled"
                                                :disabled="$stageEditionDisabled || $subRowDisabled"
                            ></x-stage.form-radio>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        <div class="col-md-6 col-lg-6 col-xl-6 mt-3">
            <div class="form-group local-forms">
                <x-form.input :id="$prefixForId.'top-take-as-standard-' . $value"
                              :name="$prefixForName . '[top][take_as_standard]'"
                              :value="$stage->fieldsGet('anterior_teeth_alignment.top.take_as_standard')"
                              :label="__('stages.take_as_standard')"
                              :disabled="$stageEditionDisabled"
                ></x-form.input>
            </div>
        </div>
    </div>

    <div @class(['input-block col4 col-md-4 col-xl-4', $rightColumnClass])>
        <label class="col-form-label">
            {{ __('stages.anterior_bottom_teeth_alignment') }}
        </label>

        @foreach(StageAnteriorTeethAlignmentEnum::getTranslationMap('stages.anterior_teeth_alignment_enums') as $value => $label)
            <x-stage.form-radio :id="$prefixForId.'bottom-value-' . $value"
                                :name="$prefixForName . '[bottom][value]'"
                                :value="$value"
                                :label="$label"
                                :checked="$stage->fieldsEqual('anterior_teeth_alignment.bottom.value', $value)"
                                :disabled="$stageEditionDisabled"
            ></x-stage.form-radio>
        @endforeach

        <div class="col-md-6 col-lg-6 col-xl-6 mt-3">
            <div class="form-group local-forms">
                <x-form.input :id="$prefixForId.'bottom-take-as-standard-' . $value"
                              :name="$prefixForName . '[bottom][take_as_standard]'"
                              :value="$stage->fieldsGet('anterior_teeth_alignment.bottom.take_as_standard')"
                              :label="__('stages.take_as_standard')"
                              :disabled="$stageEditionDisabled"
                ></x-form.input>
            </div>
        </div>
    </div>

    <div class="offset-4 col-md-8 col-lg-8 col-xl-8">
        <span class="custom-badge status-blue fs-6">
            {{ __('stages.anterior_teeth_alignment_note') }}
        </span>
    </div>
</div>
