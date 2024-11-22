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

    <div @class(['input-block col8 col-md-8 col-xl-8', $leftColumnClass])>
        <x-stage.form-radio :id="$prefixForId.'value-yes'"
                            :name="$prefixForName . '[value]'"
                            value="1"
                            :label="__('buttons.yes')"
                            :checked="$stage->fieldsChecked('periodontal_status.value')"
                            :disabled="$stageEditionDisabled"
        ></x-stage.form-radio>
        <x-stage.form-radio :id="$prefixForId.'value-no'"
                            :name="$prefixForName . '[value]'"
                            value="0"
                            :label="__('buttons.no')"
                            :checked="$stage->fieldsUnchecked('periodontal_status.value')"
                            :disabled="$stageEditionDisabled"
        ></x-stage.form-radio>

        {{ __('stages.periodontal_status_note') }}
    </div>
</div>
