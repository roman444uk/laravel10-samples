@php
    use App\Enums\Orders\Stage\StageOcclusalPlaneCorrectionEnum;use App\Enums\Orders\Stage\StageOcclusalPlaneCorrectionSchemaEnum;

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

    <div @class(['input-block col2 col-md-2 col-lg-2 col-xl-2', $leftColumnClass])>
        @foreach(StageOcclusalPlaneCorrectionEnum::getTranslationMap('stages.occlusal_plane_correction_enums') as $value => $label)
            <x-stage.form-radio :id="$prefixForId.'value-' . $value"
                                :name="$prefixForName . '[value]'"
                                :value="$value"
                                :label="$label"
                                :checked="$stage->fieldsEqual('occlusal_plane_correction.value', $value)"
                                :disabled="$stageEditionDisabled"
            ></x-stage.form-radio>
        @endforeach
    </div>

    @php
        $subRowDisabled = $stage->fieldsNotEqual('occlusal_plane_correction.value', StageOcclusalPlaneCorrectionEnum::FILL_SCHEME->value);
    @endphp
    <div
        @class(['col6 col-md-6 col-lg-6 col-xl-6 stage-sub-row', 'disabled' => $stageEditionDisabled || $subRowDisabled])
        data-sub-row="occlusal-plane-сorrection-value-fill-scheme"
    >
        <div class="doctor-submit pb-3">
            <x-teeth-schema.buttons :stage="$stage"
                                    :enum-cases="StageOcclusalPlaneCorrectionSchemaEnum::cases()"
                                    schema-group="occlusal-plane-correction"
                                    schema-field="occlusal_plane_correction"
                                    :prefix-for-id="$prefixForId"
                                    :prefix-for-name="$prefixForName"
                                    :disabled="$stageEditionDisabled || $subRowDisabled"
                                    :multiple="false"
            ></x-teeth-schema.buttons>
        </div>

        <div>
            @php
                $selectedType = $stage->fieldsGet(['occlusal_plane_correction', 'type']);
                $selectedSchema = $stage->fieldsGet(['occlusal_plane_correction', $selectedType, 'schema']);
            @endphp

            <x-teeth-schema.schema
                :stage="$stage"
                :props="collect(StageOcclusalPlaneCorrectionSchemaEnum::cases())->pluck('value')->toArray()"
                field="occlusal_plane_correction"
                :prefix-for-id="$prefixForId"
                :prefix-for-name="$prefixForName"
                schema-group="occlusal-plane-correction"
                :disabled="$stageEditionDisabled"
                :schema-selected-type="$selectedType"
                :schema-selected-data="$selectedSchema"
                :multiple="false"
            ></x-teeth-schema.schema>

            <x-teeth-schema.details
                :stage="$stage"
                field="occlusal_plane_correction"
                :props="StageOcclusalPlaneCorrectionSchemaEnum::getTranslationMap('stages')"
                schema-group="occlusal-plane-correction"
                :schema-selected-type="$selectedType"
                :multiple="false"
            ></x-teeth-schema.details>
        </div>
    </div>
</div>

