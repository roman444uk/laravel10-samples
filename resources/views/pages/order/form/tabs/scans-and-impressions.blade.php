@php
    use App\Enums\Orders\Stage\StageFileTypeEnum;use App\Enums\StageSubTabEnum;use App\Models\Stage;

    /**
     * @var bool $stageEditionDisabled
     * @var Stage $stage
     * @var string $subTab
     */
@endphp

<div class="btn-group w-100 mb-5">
    <a href="{{ route('order.edit', ['order' => $order, 'stage' => $stage, 'tab' => $tab, 'subTab' => StageSubTabEnum::UPLOAD_SCAN->value]) }}"
            @class([
                'btn',
                'btn-primary' => $subTab === StageSubTabEnum::UPLOAD_SCAN->value,
                'btn-light btn-outline-primary' => $subTab !== StageSubTabEnum::UPLOAD_SCAN->value,
            ])
    >
        {{ __ ('stages.upload_scan') }}
    </a>

    <a href="{{ route('order.edit', ['order' => $order, 'stage' => $stage, 'tab' => $tab, 'subTab' => StageSubTabEnum::TAKE_CASTS->value]) }}"
            @class([
                'btn',
                'btn-primary' => $subTab === StageSubTabEnum::TAKE_CASTS->value,
                'btn-light btn-outline-primary' => $subTab !== StageSubTabEnum::TAKE_CASTS->value,
            ])
    >
        {{ __ ('orders.treatment_tabs.take_casts') }}
    </a>
</div>

@switch($subTab)
    @case(StageSubTabEnum::UPLOAD_SCAN->value)
        <div class="form-group">
            @include('pages.order.form.tabs._photos-single', [
                'fileTypes' => collect(StageFileTypeEnum::getTranslationMap('stages.file_type_enums', 'casesScansAndImpressions'))
            ])
        </div>
        @break

    @case(StageSubTabEnum::TAKE_CASTS->value)
        <div class="form-group">
            @include('pages.order.form.tabs.take-casts')
        </div>
        @break
@endswitch
