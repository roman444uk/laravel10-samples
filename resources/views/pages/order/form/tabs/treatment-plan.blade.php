@php
    use App\Enums\Orders\StageFieldsTypeEnum;use App\Enums\Orders\StageTypeEnum;use App\Models\Stage;

    /**
     * @var bool $stageEditionDisabled
     * @var Stage $stage
     */
@endphp

<style type="text/css">
    .jaw-side-1 {
        border-right: 1px solid #999;
        border-bottom: 1px solid #999;
    }

    .jaw-side-2 {
        border-bottom: 1px solid #999;
    }

    .jaw-side-3 {

    }

    .jaw-side-4 {
        border-right: 1px solid #999;
    }

    .jaw-side-ord-1 {
        border-right: 1px solid #999;
        border-bottom: 1px solid #999;
    }

    .jaw-side-ord-2 {
        border-bottom: 1px solid #999;
    }

    .jaw-side-ord-3 {
        border-right: 1px solid #999;
    }

    .jaw-side-ord-4 {

    }

    .tooth {
        /*padding: 5px 0;*/
        border-radius: 8px;
        border: dashed transparent 2px;
        padding: 0;
        cursor: pointer;
    }

    .tooth.disabled {
        cursor: default !important;
    }

    .tooth:not(.disabled):hover {
        background-color: rgba(220, 220, 220, .5);
    }

    .tooth.selected {
        /*background-color: rgba(0, 211, 199, 0.1) !important;*/
        border: dashed #2E37A4 2px;
    }

    .tooth span:last-child {
        padding: 10px 0;
    }

    .stage-sub-row.disabled {
        opacity: .5;
    }
</style>

@if(isUserDoctor())
    <div class="btn-group w-100 mb-4">
        @foreach([
            StageFieldsTypeEnum::QUESTIONNAIRE, StageFieldsTypeEnum::TEXT, StageFieldsTypeEnum::AT_LABORATORY_DISCRETION
        ] as $typeEnum)
            <x-button type="button"
                      @class([
                            'btn stage-fields-type-button',
                            'btn-primary' => $stage->fields_type === $typeEnum->value,
                            'btn-light btn-outline-primary' => $stage->fields_type !== $typeEnum->value,
                      ])
                      :loadable="true"
                      :data-type="$typeEnum->value"
                      :disabled="$stageEditionDisabled"
            >
                @switch($typeEnum->value)
                    @case(StageFieldsTypeEnum::QUESTIONNAIRE->value)
                        {{ __ ('stages.fill_questionnaire') }}
                        @break

                    @case(StageFieldsTypeEnum::TEXT->value)
                        {{ __ ('stages.fill_with_text') }}
                        @break

                    @case(StageFieldsTypeEnum::AT_LABORATORY_DISCRETION->value)
                        {{ __ ('stages.occlusal_plane_correction_enums.at_laboratory_discretion') }}
                        @break
                @endswitch
            </x-button>
        @endforeach
    </div>
@endif

@if($stage->fields_type === StageFieldsTypeEnum::TEXT->value)
    <div id="stage-fields-summernote">{!! $stage->fields_text !!}</div>
@elseif($stage->fields_type === StageFieldsTypeEnum::AT_LABORATORY_DISCRETION->value)
    @include('pages.order.form.stage._fields-laboratory-list', [
            'title' => __('stages.treatment_areas'),
            'properties' => [
                1 => 'treatment-areas',
            ]
        ])
@else
    @include('pages.order.form.stage._fields-list', [
        'title' => __('stages.current_oral_cavity_state'),
        'properties' => [
            1 => 'constriction',
            2 => 'crowding',
            3 => 'cross-ratio',
            4 => 'canine-ratio-engel',
            5 => 'molar-ratio-engel',
            6 => 'incisors-ratio-vertical',
            7 => 'central-line-offset',
            8 => 'occlusal-line-inclination',
            9 => 'periodontal-status',
//            10 => 'peculiarities',
        ]
    ])

    @if($stage->type === StageTypeEnum::CORRECTION->value)
        @include('pages.order.form.stage._fields-list', [
            'title' => __('stages.correction_information'),
            'usePropertyIndexInFile' => false,
            'properties' => [
                1 => 'current-aligner',
                2 => 'correction-reason',
            ]
        ])
    @endif

    @include('pages.order.form.stage._fields-list', [
        'title' => __('stages.treatment_plan'),
        'properties' => [
            11 => 'treat-dental-arches',
            12 => 'central-line',
            13 => 'eliminate-crowding-by',
            14 => 'cross-ratio-lateral',
            15 => 'widening',
            16 => 'separation',
            17 => 'micro-implant',
            18 => 'teeth-eight',
            19 => 'sagittal-plane-correction',
            20 => 'replacement-installation',
            21 => 'discrepancy-incisors-canines',
            22 => 'anterior-teeth-alignment',
            23 => 'sagittal-incisor-ratio',
            24 => 'occlusal-plane-correction',
            25 => 'aligners-trimming',
            26 => 'virtual-elastic-chain',
            27 => 'sequence-tooth-movement',
        ]
    ])
@endif
