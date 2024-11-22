@php
    use App\Enums\Orders\Stage\StageAlignersTrimmingEnum;

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

    <div @class(['input-block col8 col-md-8 col-lg-8 col-xl-8', $leftColumnClass])>
        <div class="row">
            @foreach(StageAlignersTrimmingEnum::getTranslationMap('stages.aligners_trimming_enums') as $value => $label)
                <div class="col4 col-md-4 col-lg-4 col-xl-4">
                    <x-stage.form-radio :id="$prefixForId.'value-' . $value"
                                        :name="$prefixForName . '[value]'"
                                        :value="$value"
                                        :label="$label"
                                        :checked="$stage->fieldsEqual('aligners_trimming.value', $value)"
                                        :disabled="$stageEditionDisabled"
                    ></x-stage.form-radio>

                    <span class="custom-badge status-blue fs-6 text-center">
                        {{ __('stages.aligners_trimming_'.$value.'_note') }}
                    </span>
                </div>
            @endforeach

            @php
                $subRowDisabled = $stage->fieldsNotEqual('aligners_trimming.value', StageAlignersTrimmingEnum::LOW->value);
            @endphp
            <div @class(['offset-4 col-md-4 col-lg-4 col-xl-4 mt-3 stage-sub-row', 'disabled' => $subRowDisabled])
                 data-sub-row="aligners-trimming-value-low"
            >
                <div class="form-group local-forms">
                    <x-form.input :id="$prefixForId.'top-take-as-standard-' . $value"
                                  :name="$prefixForName . '[low_trimming_zone]'"
                                  type="text"
                                  :value="$subRowDisabled ? '' : $stage->fieldsGet('aligners_trimming.low_trimming_zone')"
                                  :label="__('stages.point_low_trimming_zone')"
                                  :disabled="$stageEditionDisabled || $subRowDisabled"
                    ></x-form.input>
                </div>
            </div>
        </div>
    </div>
</div>

