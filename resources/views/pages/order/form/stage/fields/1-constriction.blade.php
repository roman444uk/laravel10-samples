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
        <label class="col-form-label">
            {{ __('stages.top_jaw') }}
        </label>
        <x-stage.form-radio :id="$prefixForId.'top-yes'"
                            :name="$prefixForName . '[top]'"
                            value="1"
                            :label="__('buttons.yes')"
                            :checked="$stage->fieldsChecked('constriction.top')"
                            :disabled="$stageEditionDisabled"
        ></x-stage.form-radio>
        <x-stage.form-radio :id="$prefixForId.'top-no'"
                            :name="$prefixForName . '[top]'"
                            value="0"
                            :label="__('buttons.no')"
                            :checked="$stage->fieldsUnchecked('constriction.top')"
                            :disabled="$stageEditionDisabled"
        ></x-stage.form-radio>
    </div>

    <div @class(['input-block col4 col-md-4 col-xl-4', $rightColumnClass])>
        <label class="col-form-label">
            {{ __('stages.bottom_jaw') }}
        </label>
        <x-stage.form-radio :id="$prefixForId.'bottom-yes'"
                            :name="$prefixForName . '[bottom]'"
                            value="1"
                            :label="__('buttons.yes')"
                            :checked="$stage->fieldsChecked('constriction.bottom')"
                            :disabled="$stageEditionDisabled"
        ></x-stage.form-radio>
        <x-stage.form-radio :id="$prefixForId.'bottom-no'"
                            :name="$prefixForName . '[bottom]'"
                            value="0"
                            :label="__('buttons.no')"
                            :checked="$stage->fieldsUnchecked('constriction.bottom')"
                            :disabled="$stageEditionDisabled"
        ></x-stage.form-radio>
    </div>
</div>
