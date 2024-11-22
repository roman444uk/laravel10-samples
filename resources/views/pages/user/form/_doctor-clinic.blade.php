@php
    use App\Support\Arr;

    /**
     * @var App\Models\DoctorClinic $doctorClinic
     * @var integer $index
     */

    $index = $index ?? null;
@endphp

<div class="row doctor-clinic">
    <div class="col-md-11 col-lg-11">
        <div class="form-group local-forms">
            <input type="hidden" name="doctor[clinics][{{ $index }}][id]" data-column="id" value="{{ $doctorClinic?->id }}">
            <input type="hidden" name="doctor[clinics][{{ $index }}][data]" data-column="data">

            <x-form.input class="floating doctor-clinic-input"
                          type="text"
                          name="doctor[clinics][{{ $index }}][name]"
                          :value="Arr::getValue($doctorClinic->clinic->data ?? [], 'data.name.short_with_opf')"
                          :label="__('doctors.clinic')"
                          data-column="name"
            ></x-form.input>
        </div>
    </div>
    <div class="col-md-1 col-lg-1">
        <a href="javascript:;" class="btn btn-danger clinic-remove">
            <i class="fa fa-plus"></i> {{ __('buttons.remove') }}
        </a>
    </div>
</div>
