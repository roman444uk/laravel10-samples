@php
    /**
     * @var \App\Models\Check $check
     */
@endphp

<x-modal id="check-reject-form-modal">
    <x-slot:title>
        {{ __('checks.adding_check') }}
    </x-slot:title>

    <x-slot:buttons>
        <x-button id="check-reject-form-button"
                  class="btn btn-secondary"
                  type="button"
                  :loadable="true"
        >{{ __('buttons.save') }}</x-button>
    </x-slot:buttons>

    <form id="check-reject-form">
        @csrf

        <input type="hidden" name="id">

        <div class="form-group local-forms">
            <x-form.textarea id="reject-reason"
                             class="floating"
                             name="reject_reason"
                             rows="5"
                             :label="__('checks.reject_reason')"
            ></x-form.textarea>
        </div>
    </form>
</x-modal>
