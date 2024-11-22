@php
    use App\Classes\Helpers\Db\StageHelper;
    use App\Support\Utilities\DateTime;
    use App\Models\Address;
    use App\Models\Stage;
    use Illuminate\Database\Eloquent\Collection;

    /**
     * @var Collection|Address[] $addressesCollection
     * @var Stage $stage
     * @var bool $stageEditionDisabled
     */

    $disabled = !StageHelper::isTakeCastsAvailableForEdition($stage);
@endphp

<div class="row">
    <div class="col-12">
        <div class="form-group local-forms">
            @include('pages.stage._take-casts-address-select', [
                'addressesCollection' => $addressesCollection,
                'stage' => $stage,
                'disabled' => $disabled,
            ])
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-md-6 col-lg-6 col-xl-6">
        <div class="form-group local-forms">
            <x-form.input id="take-casts-date"
                          class="datetimepicker"
                          type="text"
                          name="take_casts_date"
                          :value="old('take_casts', DateTime::renderDate($stage->take_casts_date))"
                          :label="__('common.date')"
                          input-wrap-icon="cal-icon"
                          :required="true"
                          :disabled="$disabled"
            ></x-form.input>
        </div>
    </div>
    <div class="col-md-12 col-md-6 col-lg-6 col-xl-6">
        <div class="form-group local-forms">
            <x-form.input id="take-casts-time"
                          class="datetimepicker"
                          type="text"
                          name="take_casts_time"
                          :value="old('take_casts', DateTime::renderTime($stage->take_casts_time))"
                          :label="__('common.time')"
                          input-wrap-icon="time-icon"
                          :required="true"
                          :disabled="$disabled"
            ></x-form.input>
        </div>
    </div>
</div>

@if(isUserDoctor() && !$disabled)
    <div class="row">
        <div class="col-12 text-center">
            <x-button id="take-casts-button"
                      type="button"
                      class="btn btn-primary"
                      :loadable="true"
            >{{ __('orders.treatment_tabs.take_casts') }}</x-button>
        </div>
    </div>
@endif
