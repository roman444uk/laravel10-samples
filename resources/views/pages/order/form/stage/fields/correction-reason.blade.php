@php
    use App\Enums\Orders\Stage\StageCorrectionReasonEnum;

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

    <div @class(['col8 col-md-8 col-lg-8 col-xl-8'])>
        @foreach(StageCorrectionReasonEnum::getTranslationMap('stages.correction_reason_enums') as $value => $label)
            <x-stage.form-checkbox :id="$prefixForId.'value-' . $value"
                                   :name="$prefixForName . '[value][' . $value . ']'"
                                   :value="$value"
                                   :label="$label"
                                   :checked="$stage->fieldsExists('correction_reason.value', $value)"
            ></x-stage.form-checkbox>
        @endforeach

        <div class="input-block local-forms mt-3">
            <label>{{ __('stages.explain') }}</label>
            <textarea rows="5" cols="5" class="form-control"
                      name="{{ $prefixForName . '[description]' }}"
            >{{ $stage->fieldsGet('correction_reason.description') }}</textarea>
        </div>
    </div>
</div>

