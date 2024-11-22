@php
    use App\Enums\Orders\Stage\StageRatioEngelEnum;

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
            {{ __('stages.on_right') }}
        </label>

        @foreach(StageRatioEngelEnum::getTranslationMap('stages.ratio_engel_enums') as $value => $label)
            <x-stage.form-radio :id="$prefixForId.'right-' . $value"
                                :name="$prefixForName . '[right]'"
                                :value="$value"
                                :label="$label"
                                :checked="$stage->fieldsEqual('canine_ratio_engel.right', $value)"
                                :disabled="$stageEditionDisabled"
            ></x-stage.form-radio>
        @endforeach
    </div>

    <div @class(['input-block col4 col-md-4 col-xl-4', $rightColumnClass])>
        <label class="col-form-label">
            {{ __('stages.on_left') }}
        </label>

        @foreach(StageRatioEngelEnum::getTranslationMap('stages.ratio_engel_enums') as $value => $label)
            <x-stage.form-radio :id="$prefixForId.'left-' . $value"
                                :name="$prefixForName . '[left]'"
                                :value="$value"
                                :label="$label"
                                :checked="$stage->fieldsEqual('canine_ratio_engel.left', $value)"
                                :disabled="$stageEditionDisabled"
            ></x-stage.form-radio>
        @endforeach
    </div>
</div>
