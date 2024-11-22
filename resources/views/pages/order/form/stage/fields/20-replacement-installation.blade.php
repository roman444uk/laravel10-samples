@php
    use App\Enums\Orders\Stage\StageReplacementInstallationSchemaEnum;

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
        <div class="row">
            <div class="col-md-8 col-lg-8 col-xl-8">
                <div class="doctor-submit pb-3">
                    <x-teeth-schema.buttons :stage="$stage"
                                            :enum-cases="StageReplacementInstallationSchemaEnum::cases()"
                                            schema-group="replacement-installation"
                                            schema-field="replacement_installation"
                                            :prefix-for-id="$prefixForId"
                                            :prefix-for-name="$prefixForName"
                                            :disabled="$stageEditionDisabled"
                    ></x-teeth-schema.buttons>
                </div>
            </div>
            <div class="col-md-4 col-lg-4 col-xl-4">
                <span class="custom-badge status-blue fs-6">{{ __('stages.replacement_installation_note') }}</span>
            </div>
        </div>

        <div class="mt-4">
            <x-teeth-schema.schema
                    :stage="$stage"
                    field="replacement_installation"
                    :props="collect(StageReplacementInstallationSchemaEnum::cases())->pluck('value')->toArray()"
                    :prefix-for-id="$prefixForId"
                    :prefix-for-name="$prefixForName"
                    schema-group="replacement-installation"
                    :disabled="$stageEditionDisabled"
            ></x-teeth-schema.schema>

            <x-teeth-schema.details
                    :stage="$stage"
                    field="replacement_installation"
                    :props="StageReplacementInstallationSchemaEnum::getTranslationMap('stages')"
                    schema-group="replacement-installation"
            ></x-teeth-schema.details>
        </div>
    </div>
</div>
