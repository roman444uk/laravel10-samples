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

    <div @class(['input-block col4 col-md-4 col-lg-4 col-xl-4', $leftColumnClass])>
        <label class="col-form-label">
            {{ __('stages.top_jaw') }}
        </label>
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xl-6">
                <x-form.input :id="$prefixForId.'top-'"
                              :name="$prefixForName . '[top]'"
                              :value="$stage->fieldsGet('current_aligner.top')"
                ></x-form.input>
            </div>
        </div>
    </div>

    <div @class(['input-block col4 col-md-4 col-xl-4', $rightColumnClass])>
        <label class="col-form-label">
            {{ __('stages.bottom_jaw') }}
        </label>
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xl-6">
                <x-form.input :id="$prefixForId.'bottom-'"
                              :name="$prefixForName . '[bottom]'"
                              :value="$stage->fieldsGet('current_aligner.bottom')"
                ></x-form.input>
            </div>
        </div>
    </div>
</div>
