@php
    use App\Classes\Helpers\FeedbackHelper;
@endphp

<x-modal id="address-form-modal">
    <x-slot:title>
        {{ __('addresses.adding_address') }}
    </x-slot:title>

    <x-slot:buttons>
        <x-button id="address-save-button"
                  class="btn btn-secondary"
                  type="button"
                  :loadable="true"
        >{{ __('buttons.save') }}</x-button>
    </x-slot:buttons>

    <form id="address-form">
        @csrf

        <input type="hidden" name="id">
        <input type="hidden" name="data">
        <input type="hidden" name="stage">
        <input type="hidden" name="take-casts">
        <input type="hidden" name="production">

        <div class="form-group local-forms">
            <x-form.input id="address"
                          class="floating"
                          type="text"
                          name="address"
                          :label="__('stages.delivery_address')"
            ></x-form.input>
        </div>
    </form>
</x-modal>
