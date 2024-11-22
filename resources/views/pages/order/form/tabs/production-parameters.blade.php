@php
    use App\Classes\Helpers\Db\CheckHelper;
    use App\Classes\Helpers\Db\StageHelper;
    use App\Models\Production;
    use App\Models\Stage;

    /**
     * @var bool $stageEditionDisabled
     * @var Stage $stage
     */
@endphp

<div class="text-end">
    @can('createAny', [Production::class, $stage])
        <x-button type="button"
                  class="btn btn-primary submit-form me-2"
                  id="production-add-button"
                  data-bs-toggle="modal"
                  data-bs-target="#production-form-modal"
                  fa-icon="plus"
                  :disabled="!can('create', [Production::class, $stage])"
        >
            {{ __ ('buttons.add') }}
        </x-button>
    @endcan

    @if($stage->checkAccepted)
        @include('pages.production.index._table', [
            'productionsCollection' => $stage->checkAccepted->productions,
        ])
    @endif
</div>
