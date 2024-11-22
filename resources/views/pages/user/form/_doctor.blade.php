@php
    use App\Support\Utilities\DateTime;

    /**
     * @var Illuminate\Support\Collection $citiesCollection
     * @var bool $doctorDetailsRequired
     * @var App\Models\User $user
     */
@endphp

<div class="card-box">
    <h3 class="card-title">
        {{ __('doctors.specialization') }}
    </h3>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group local-forms">
                <x-form.input id="experience-from"
                              class="datetimepicker"
                              type="text"
                              name="doctor[experience_from]"
                              :value="old('experience_from', DateTime::renderDate($user->doctor->experience_from))"
                              :label="__('doctors.experience_from')"
                              input-wrap-icon="cal-icon"
                              :required="$doctorDetailsRequired"
                ></x-form.input>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group local-forms">
                <x-form.select id="experience-with-aligners"
                               class="select"
                               name="doctor[experience_with_aligners]"
                               :options="[
                                    '' => __('common.not_chosen'),
                                    'invisalign' => __('Имею сертификат Invisalign®'),
                                    'flexiligner' => __('Имею сертификат Flexiligner'),
                                    'starsmile' => __('Имею сертификат StarSmile'),
                                    'other' => __('Имею другой сертификат работы с элайнерами'),
                               ]"
                               :selected-value="old('experience_with_aligners', $user->doctor->experience_with_aligners)"
                               :label="__('Опыт работы на элайнерах')"
                               :required="$doctorDetailsRequired"
                ></x-form.select>
            </div>
        </div>
    </div>
</div>

<div class="card-box">
    <h3 class="card-title">
        {{ __('doctors.clinics') }}
    </h3>
    <div class="doctor-clinics">
        @foreach($user->doctor->doctorClinics as $index => $doctorClinic)
            @include('pages.user.form._doctor-clinic', [
                'index' => $index,
                'doctorClinic' => $doctorClinic,
            ])
        @endforeach
    </div>
    <div class="">
        <a href="javascript:;" class="btn btn-primary" id="clinic-add-button">
            <i class="fa fa-plus"></i> {{ __('doctors.add_clinic') }}
        </a>
    </div>
</div>
