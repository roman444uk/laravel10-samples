@php
    use App\Enums\Orders\Stage\StageCentralLineDueToEnum;use App\Enums\Orders\Stage\StageCentralLineEnum;

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

    <div @class(['col4 col-md-4 col-xl-4'])>
        <div class="profile-check-blk input-block">
            <label class="col-form-label">
                {{ __('stages.top_central_line') }}
            </label>

            <div class="remember-me">
                @foreach(StageCentralLineEnum::getTranslationMap('stages.central_line_enums') as $value => $label)
                    <x-stage.form-radio :id="$prefixForId.'top-' . $value"
                                        :name="$prefixForName . '[top]'"
                                        :value="$value"
                                        :label="$label"
                                        :checked="$stage->fieldsEqual('central_line.top', $value)"
                                        :disabled="$stageEditionDisabled"
                    ></x-stage.form-radio>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col4 col-md-4 col-xl-4">
        <div class="profile-check-blk input-block">
            <label class="col-form-label">
                {{ __('stages.bottom_central_line') }}
            </label>

            <div class="remember-me">
                @foreach(StageCentralLineEnum::getTranslationMap('stages.central_line_enums') as $value => $label)
                    <x-stage.form-radio :id="$prefixForId.'bottom-' . $value"
                                        :name="$prefixForName . '[bottom]'"
                                        :value="$value"
                                        :label="$label"
                                        :checked="$stage->fieldsEqual('central_line.bottom', $value)"
                                        :disabled="$stageEditionDisabled"
                    ></x-stage.form-radio>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col8 offset-4 col-md-8 col-md-8 col-xl-8">
        <span class="custom-badge status-blue fs-6">{{ __('stages.central_line_note') }}</span>
    </div>

    @php
        $subRowDisabled = $stage->fieldsNotExists('central_line.top', [
            StageCentralLineEnum::MOVE_TO_LEFT->value, StageCentralLineEnum::MOVE_TO_RIGHT->value
        ]) && $stage->fieldsNotExists('central_line.bottom', [
            StageCentralLineEnum::MOVE_TO_LEFT->value, StageCentralLineEnum::MOVE_TO_RIGHT->value
        ]);
    @endphp
    <div
        @class(['col8 offset-4 col-md-8 col-md-8 col-xl-8 stage-sub-row', 'disabled' => $subRowDisabled, $rightColumnClass])
        data-sub-row="central-line"
    >
        <label class="col-form-label">
            {{ __('stages.due_to') }}
        </label>

        <div class="remember-me">
            @foreach(StageCentralLineDueToEnum::getTranslationMap('stages.central_line_due_to_enums') as $value => $label)
                <x-stage.form-checkbox :id="$prefixForId.'due-to-' . $value"
                                       :name="$prefixForName . '[due_to][' . $value . ']'"
                                       :value="$value"
                                       :label="$label"
                                       :checked="$stage->fieldsExists('central_line.due_to', $value) && !$subRowDisabled"
                                       :disabled="$subRowDisabled"
                ></x-stage.form-checkbox>
            @endforeach
        </div>
    </div>
</div>
