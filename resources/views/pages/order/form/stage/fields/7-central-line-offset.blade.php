@php
    use App\Enums\Orders\Stage\StageCentralLineOffsetJawEnum;

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
                            :checked="$stage->fieldsChecked('central_line_offset.value')"
                            :disabled="$stageEditionDisabled"
        ></x-stage.form-radio>
        <x-stage.form-radio :id="$prefixForId.'value-no'"
                            :name="$prefixForName . '[value]'"
                            value="0"
                            :label="__('buttons.no')"
                            :checked="$stage->fieldsUnchecked('central_line_offset.value')"
                            :disabled="$stageEditionDisabled"
        ></x-stage.form-radio>
    </div>

    <div
            @class(['input-block col4 col-md-4 col-xl-4 stage-sub-row', 'disabled' => $stage->fieldsUnchecked('central_line_offset.value'), $rightColumnClass])
            data-sub-row="central-line-offset-types"
    >
        <div class="profile-check-blk input-block">
            <div class="remember-me">
                @foreach(StageCentralLineOffsetJawEnum::getTranslationMap('stages.central_line_offset_enums') as $value => $label)
                    @php
                        $subRowDisabled = $stage->fieldsUnchecked('central_line_offset.value');
                    @endphp
                    <x-stage.form-checkbox :id="$prefixForId.'types-' . $value"
                                           :name="$prefixForName . '[jaw]['.$value.']'"
                                           :value="$value"
                                           :label="$label"
                                           :checked="$stage->fieldsExists('central_line_offset.jaw', $value) && !$subRowDisabled"
                                           :disabled="$stageEditionDisabled || $subRowDisabled"
                    ></x-stage.form-checkbox>
                @endforeach
            </div>
        </div>
    </div>
</div>
