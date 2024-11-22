@php
    use App\Classes\Helpers\FeedbackHelper;use App\Enums\Orders\PatientGenderEnum;
@endphp

<x-modal id="patient-form-modal">
    <x-slot:title>
        {{ __('orders.add_lk_contact') }}
    </x-slot:title>

    <x-slot:buttons>
        <x-button id="patient-save-button"
                  class="btn btn-secondary"
                  type="button"
                  :loadable="true"
        >{{ __('buttons.save') }}</x-button>
    </x-slot:buttons>

    <form id="patient-form">
        @csrf

        <input type="hidden" name="id">

        <div class="form-group local-forms">
            <x-form.input id="full_name"
                          class="floating"
                          type="text"
                          name="full_name"
                          :value="old('full_name')"
                          :label="__('patients.full_name')"
                          :required="true"
            ></x-form.input>
        </div>

        <div class="form-group local-forms">
            <x-form.input id="phone"
                          class="floating"
                          type="text"
                          name="phone"
                          :value="old('phone')"
                          :label="__('patients.phone')"
                          data-input-mask="phone"
                          :required="true"
            ></x-form.input>
        </div>

        <div class="form-group local-forms">
            <x-form.input id="email"
                          class="floating"
                          type="text"
                          name="email"
                          :value="old('email')"
                          :label="__('patients.email')"
                          placeholder="example@example.com"
                          :required="true"
            ></x-form.input>
        </div>

        <div class="form-group" style="margin-bottom: 30px;">
            <div class="input-block select-gender">
                <label class="gen-label" style="margin-left: 14px;">
                    {{ __('patients.gender') }} <span class="login-danger">*</span>
                </label>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" name="gender" class="form-check-input mt-0"
                               value="{{ PatientGenderEnum::MALE->value }}">
                        {{ __('patients.male') }}
                    </label>
                </div>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" name="gender" class="form-check-input mt-0"
                               value="{{ PatientGenderEnum::FEMALE->value }}">
                        {{ __('patients.female') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group local-forms">
            <x-form.input id="birth-date"
                          class="datetimepicker"
                          type="text"
                          name="birth_date"
                          :value="old('birth_date')"
                          :label="__('patients.birth_date')"
                          input-wrap-icon="cal-icon"
                          :required="true"
            ></x-form.input>
        </div>

        <div class="form-group local-forms">
            <x-form.textarea id="comment"
                             name="comment"
                             :label="__('patients.comment')"
            ></x-form.textarea>
        </div>
    </form>
</x-modal>
