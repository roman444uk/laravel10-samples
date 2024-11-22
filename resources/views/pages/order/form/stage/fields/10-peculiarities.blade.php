@php
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
                <div class="doctor-submit pb-3">
                    <button type="button" class="btn btn-primary cancel-form me-2" @disabled($stageEditionDisabled)>
                        {{ __('stages.planning_removal') }}
                    </button>
                    <button type="button" class="btn btn-primary cancel-form me-2" @disabled($stageEditionDisabled)>
                        {{ __('stages.removal_according_laboratory_recommendation') }}
                    </button>
                    <button type="button" class="btn btn-primary cancel-form me-2" @disabled($stageEditionDisabled)>
                        {{ __('stages.do_not_install_attachments') }}
                    </button>
                </div>
            </div>
        </div>

{{--        <div class="row">--}}
{{--            <x-svg.teeth></x-svg.teeth>--}}
{{--        </div>--}}

        <div class="row">
            <x-teeth-schema></x-teeth-schema>
        </div>
    </div>
</div>
