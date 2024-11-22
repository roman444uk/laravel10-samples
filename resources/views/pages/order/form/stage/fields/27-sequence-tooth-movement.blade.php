@php
    use App\Enums\Orders\Stage\StageSequenceToothMovementEnum;

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
        @foreach(StageSequenceToothMovementEnum::getTranslationMap('stages.sequence_tooth_movement_enums') as $value => $label)
            <x-stage.form-radio :id="$prefixForId.'value-' . $value"
                                :name="$prefixForName . '[value]'"
                                :value="$value"
                                :label="$label"
                                :checked="$stage->fieldsEqual('sequence_tooth_movement.value', $value)"
                                :disabled="$stageEditionDisabled"
            ></x-stage.form-radio>
        @endforeach

        <div class="input-block local-forms mt-3">
            {{--            <label></label>--}}
            <textarea rows="5" cols="5" class="form-control"
                      name="{{ $prefixForName . '[description]' }}"
                      @disabled($stageEditionDisabled)
            >{{ $stage->fieldsGet('sequence_tooth_movement.description') }}</textarea>
        </div>
    </div>
</div>

