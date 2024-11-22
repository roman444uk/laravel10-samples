@php
    use App\Enums\Orders\Stage\StageSeparationEnum;

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
        <div class="profile-check-blk input-block">
            <div class="remember-me">
                @foreach(StageSeparationEnum::getTranslationMap('stages.separation_enums') as $value => $label)
                    <x-stage.form-checkbox :id="$prefixForId.'value-' . $value"
                                           :name="$prefixForName . '[value]['.$value.']'"
                                           :value="$value"
                                           :label="$label"
                                           :checked="$stage->fieldsExists('separation.value', $value)"
                                           :disabled="$stageEditionDisabled"
                    ></x-stage.form-checkbox>
                @endforeach
            </div>
        </div>
    </div>
</div>
