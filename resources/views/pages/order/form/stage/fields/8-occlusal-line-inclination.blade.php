@php
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
                            :checked="$stage->fieldsChecked('occlusal_line_inclination.value')"
                            :disabled="$stageEditionDisabled"
        ></x-stage.form-radio>
        <x-stage.form-radio :id="$prefixForId.'value-no'"
                            :name="$prefixForName . '[value]'"
                            value="0"
                            :label="__('buttons.no')"
                            :checked="$stage->fieldsUnchecked('occlusal_line_inclination.value')"
                            :disabled="$stageEditionDisabled"
        ></x-stage.form-radio>
    </div>
</div>
