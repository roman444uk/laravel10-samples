@php
    use App\Enums\Orders\Stage\StageIncisorsRatioVerticalEnum;

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
        @foreach(StageIncisorsRatioVerticalEnum::getTranslationMap('stages.incisors_ratio_vertical_enums') as $value => $label)
            <x-stage.form-radio :id="$prefixForId.'value-' . $value"
                                :name="$prefixForName . '[value]'"
                                :value="$value"
                                :label="$label"
                                :checked="$stage->fieldsEqual('incisors_ratio_vertical.value', $value)"
                                :disabled="$stageEditionDisabled"
            ></x-stage.form-radio>
        @endforeach
    </div>
</div>
