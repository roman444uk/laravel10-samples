@php
    use App\Enums\Orders\Stage\StageTeethEightEnum;

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
            <div class="col-md-12 col-lg-12 col-xl-12">
                <span class="custom-badge status-red fs-6">{{ __('stages.teeth_eight_note') }}</span>
            </div>

            @foreach([1, 2, 4, 3] as $side)
                <div @class(['col-md-6 col-xl-6', 'jaw-side-' . $side])>
                    <div class="row">
                        <div class="col-md-9 col-xl-9">
                            @foreach(StageTeethEightEnum::getTranslationMap('stages.teeth_eight_enums') as $value => $label)
                                <x-stage.form-radio :id="$prefixForId.$side.'-8-' . $value"
                                                    :name="$prefixForName . '['.$side.'][8]'"
                                                    :value="$value"
                                                    :label="$label"
                                                    :checked="$stage->fieldsEqual('teeth_eight.' . $side . '.8', $value)"
                                                    :disabled="$stageEditionDisabled"
                                ></x-stage.form-radio>
                            @endforeach
                        </div>
                        <div class="col-md-3 col-xl-3 text-center">
                            {{ $side . '.8' }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
