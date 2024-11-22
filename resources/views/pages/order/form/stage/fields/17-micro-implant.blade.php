@php
    use App\Enums\Orders\Stage\StageMicroImplantAreasEnum;use App\Enums\Orders\Stage\StageMicroImplantEnum;

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
        @foreach(StageMicroImplantEnum::getTranslationMap('stages.micro_implant_enums') as $value => $label)
            <x-stage.form-radio :id="$prefixForId.'value-' . $value"
                                :name="$prefixForName . '[value]'"
                                :value="$value"
                                :label="$label"
                                :checked="$stage->fieldsEqual('micro_implant.value', $value)"
                                :disabled="$stageEditionDisabled"
            ></x-stage.form-radio>
        @endforeach
    </div>

    @php
        $subRowDisabled = $stage->fieldsNotExists('micro_implant.value', [
            StageMicroImplantEnum::YES->value, StageMicroImplantEnum::BY_LABORATORY_RECOMMENDATION->value,
        ]);
    @endphp
    <div @class(['col4 col-md-4 col-xl-4 stage-sub-row', 'disabled' => $subRowDisabled, $rightColumnClass])
         data-sub-row="micro-implant-areas"
    >
        <label class="col-form-label">
            {{ __('stages.what_locations') }}
        </label>
        @foreach(StageMicroImplantAreasEnum::getTranslationMap('stages.micro_implant_areas_enums') as $value => $label)
            <x-stage.form-checkbox :id="$prefixForId.'areas-' . $value"
                                   :name="$prefixForName . '[areas]['.$value.']'"
                                   :value="$value"
                                   :label="$label"
                                   :checked="$stage->fieldsExists('micro_implant.areas', $value) && !$subRowDisabled"
                                   :disabled="$stageEditionDisabled || $subRowDisabled"
            ></x-stage.form-checkbox>
        @endforeach
    </div>

    <div @class(['input-block col8 col-md-8 col-xl-8 offset-4', $leftColumnClass])>
        <span class="custom-badge status-blue fs-6">
            {{ __('stages.micro_implant_note') }}
        </span>
    </div>
</div>
