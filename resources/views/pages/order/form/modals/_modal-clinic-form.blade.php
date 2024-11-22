@php
    use App\Classes\Helpers\FeedbackHelper;
@endphp

<x-modal id="clinic-form-modal">
    <x-slot:title>
        {{ __('orders.adding_clinic') }}
    </x-slot:title>

    <x-slot:buttons>
        <x-button id="clinic-save-button"
                           class="btn btn-secondary"
                           type="button"
                           :loadable="true"
        >{{ __('buttons.save') }}</x-button>
    </x-slot:buttons>

    <form id="clinic-form">
        @csrf

        <input type="hidden" name="data">

        <div class="form-group local-forms">
            <x-form.input id="da-data"
                          class="floating doctor-clinic-input"
                          type="text"
                          :label="__('doctors.clinic')"
                          data-column="name"
            ></x-form.input>
        </div>
    </form>
</x-modal>
